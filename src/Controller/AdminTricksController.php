<?php

namespace App\Controller;

use App\Entity\Figure;

use App\Entity\Image;
use App\Form\ImageFormType;
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

            $figure = $entityCreator->createOrEditFigure($form, $entityManager,true);
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
    public function editTrick(Figure $figure,$id,FieldGenerator $fieldGenerator,Request $request,EntityObjectCreator $entityObjectCreator,EntityManagerInterface $entityManager,ImageRepository $imageRepository,Filesystem $filesystem)
    {
        $form = $this->createForm(TrickFormType::class,$figure,["is_edit"=>true]);
        $imageForm = $this->createForm(ImageFormType::class);

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
//            dd($imageForm["image"]->getData());
            $newImageName=uniqid() . $imageForm["image"]->getData()->getClientOriginalName();
            $uploadedFile = $imageForm["image"]->getData();
            if (!empty($imageForm["idImage"]->getData())) {
                $image = $imageRepository->findOneBy(["id" => $imageForm["idImage"]->getData()]);
                $filesystem->remove("images/".$image->getName());
                $image->setName($newImageName);
                $entityManager->flush();
                $imageForm["image"]->getData()->move('images', $newImageName);
                $this->addFlash('success',"L'image été modifiée!");
            }
            else {

                $entityObjectCreator->createImage($uploadedFile,$newImageName,$figure,$entityManager);
            }


        }

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $figure = $form->getData();
            $entityManager->persist($figure);
            $entityManager->flush();
            $this->addFlash('success',"Le trick à été modifié!");
            return $this->redirectToRoute("app_homepage");
        }

        $nbImages = TrickFormType::NB_IMAGE;
        $nbVideos = TrickFormType::NB_VIDEO;

        return $this->render("admin_tricks/edit.html.twig",
            [
                "trickForm"=>$form->createView(),
                "imageForm" => $imageForm,
                "nbImages" => $nbImages,
                "nbVideos" => $nbVideos,
                "trick" => $figure,]);
    }

    /**
     * @Route("tricks/edit/image/{id}/{new=false}",name="admin_edit_image")
     */
    public function editImage(Image $image,$id,$new,ImageRepository $imageRepository)
    {
        if ($new==false) {
            $image->setName();
        }
        return $this->redirectToRoute('admin_tricks_delete');
    }


    /*public function editTrick(Figure $figure,$id,FieldGenerator $fieldGenerator,Request $request,EntityObjectCreator $entityObjectCreator,EntityManagerInterface $entityManager,ImageRepository $imageRepository)
    {


        $form = $this->createForm(TrickFormType::class,$figure);
        $fieldGenerator->addImageFields($figure,$form);
        $fieldGenerator->addVideoFields($figure,$form);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityObjectCreator->createOrEditFigure($form, $entityManager,false,$figure);
            $entityObjectCreator->createImages($form,$figure,$entityManager);
            $entityObjectCreator->createVideos($form,$figure,$entityManager);
            $entityManager->flush();
            return $this->redirectToRoute('app_homepage');
        }


        return $this->render("admin_tricks/edit.html.twig",
        [
            "trickForm"=>$form->createView(),
            "trick"=>$figure]);
    }*/
}
