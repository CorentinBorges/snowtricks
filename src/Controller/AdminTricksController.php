<?php

namespace App\Controller;

use App\Entity\Figure;

use App\Entity\Image;
use App\Entity\Video;
use App\Form\ImageFormType;
use App\Form\TrickFormType;
use App\Form\VideoFormType;
use App\Repository\BaseRepository;
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
    public function add(Request $request, EntityManagerInterface $entityManager,ImageRepository $imageRepository, VideoRepository $videoRepository, FigureRepository $figureRepository)
    {
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form['name']->getData();
            $description = $form['description']->getData();
            $groupe = $form['groupe']->getData();

            $figure = $figureRepository->createFigure($name,$description,$groupe);
            $imageRepository->createImages($form, $figure);
            $videoRepository->createVideos($form,$figure);
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
    public function editTrick(Figure $figure,$id,FieldGenerator $fieldGenerator,Request $request,EntityObjectCreator $entityObjectCreator,EntityManagerInterface $entityManager,ImageRepository $imageRepository,Filesystem $filesystem,VideoRepository $videoRepository)
    {
        $form = $this->createForm(TrickFormType::class,$figure,["is_edit"=>true]);
        $imageForm = $this->createForm(ImageFormType::class);
        $videoForm = $this->createForm(VideoFormType::class);

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {

            $newImageName=uniqid() . $imageForm["image"]->getData()->getClientOriginalName();
            $uploadedFile = $imageForm["image"]->getData();

            if (!empty($imageForm["idImage"]->getData())) {
                $imageRepository->editImage($imageForm['idImage']->getData(), $newImageName, $imageForm['image']->getData());
                $this->addFlash('success',"L'image été modifiée!");
            }

            $imageRepository->createImage($uploadedFile,$newImageName,$figure);
            $figure->setModifiedAtNow();
            $entityManager->flush();
            return $this->redirectToRoute('admin_tricks_edit',['id'=>$id]);
        }

        $videoForm->handleRequest($request);
        if ($videoForm->isSubmitted() && $videoForm->isValid()) {

            if (!empty($videoForm['id']->getData())) {
                $videoRepository->editVideo($videoForm['id']->getData(),$videoForm['link']->getData());
                $this->addFlash("success","La vidéo à été modifiée");

            }

            $videoRepository->createVideo($figure,$videoForm['link']->getData());
            $figure->setModifiedAtNow();
            $entityManager->flush();
            $this->addFlash('success',"Votre vidéo à été ajoutée");
            return $this->redirectToRoute('admin_tricks_edit',['id'=>$id]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $figure = $form->getData();
            $entityManager->persist($figure);
            $figure->setModifiedAtNow();
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
                "videoForm" => $videoForm,
                "nbImages" => $nbImages,
                "nbVideos" => $nbVideos,
                "trick" => $figure,]);
    }

    /**
     * @Route("tricks/delete/image/{id}",name="admin_image_delete")
     */
    public function deletePic(Image $image,$id, EntityManagerInterface $entityManager,ImageRepository $imageRepository)
    {
        $trickId = $image->getFigure()->getId();
        $imageRepository->deleteFromDatabase($image);
        $this->addFlash("success","L'image a été supprimée");
        return $this->redirectToRoute('admin_tricks_edit', ['id' => $trickId]);
    }


    /**
     * @Route("tricks/delete/video/{id}",name="admin_video_delete")
     */
    public function deleteVideo(Video $video,VideoRepository $videoRepository)
    {
        $trickId = $video->getFigure()->getId();
        $videoRepository->deleteFromDatabase($video);
        $this->addFlash("success","La vidéo a été supprimée");
        return $this->redirectToRoute('admin_tricks_edit', ['id' => $trickId]);
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
