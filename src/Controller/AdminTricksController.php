<?php

namespace App\Controller;

use App\Entity\Figure;

use App\Form\TrickFormType;
use App\Repository\FigureRepository;
use App\Repository\ImageRepository;
use App\Repository\MessageRepository;
use App\Repository\VideoRepository;
use App\Service\EntityObjectCreator;
use App\Service\FieldGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminTricksController extends AbstractController
{
    /**
     * @Route("/tricks/new", name="admin_tricks_new")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param EntityObjectCreator $entityCreator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager, EntityObjectCreator $entityCreator)
    {
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $figure = $entityCreator->createFigure($form, $entityManager);
            $entityCreator->createImages($form, $figure, $entityManager);
            $entityCreator->createVideos($form,$figure, $entityManager);
            $entityManager->flush();

            $this->addFlash("success","Yes!!! Votre trick à bien été ajouté!! ❄❄❄");
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('admin_tricks/new.html.twig', [
            'trickForm' => $form->createView(),

        ]);
    }

    /**
     * @Route("tricks/delete/{id}",name="admin_tricks_delete")
     * @param Figure $figure
     * @param ImageRepository $imageRepository
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @param VideoRepository $videoRepository
     * @param FigureRepository $figureRepository
     * @param MessageRepository $messageRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTrick(Figure $figure, ImageRepository $imageRepository, $id, EntityManagerInterface $entityManager, VideoRepository $videoRepository, FigureRepository $figureRepository,MessageRepository $messageRepository,Filesystem $filesystem)
    {
        $imageRepository->deletePicsFromTrick($id, $filesystem);
        $videoRepository->deleteVideosFromTrick($id);
        $messageRepository->deleteMessagesFromTrick($id);
        $figureRepository->deleteTrick($id);
        $entityManager->flush();
        $this->addFlash('success','Le trick à bien été supprimé');
        return $this->redirectToRoute('app_homepage');
    }

    /**
     * @Route("tricks/edit/{id}",name="admin_tricks_edit")
     * @param Figure $figure
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editTrick(Figure $figure,FieldGenerator $fieldGenerator)
    {


        $form = $this->createForm(TrickFormType::class,$figure);
        $fieldGenerator->addImageFields($figure,$form);
        $fieldGenerator->addVideoFields($figure,$form);


        return $this->render("admin_tricks/edit.html.twig",
        [
            "trickForm"=>$form->createView()]);
    }
}
