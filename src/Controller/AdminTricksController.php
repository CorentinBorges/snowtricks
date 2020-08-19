<?php

namespace App\Controller;

use App\Entity\Figure;

use App\Entity\Image;
use App\Entity\Video;
use App\Form\ImageFormType;
use App\Form\TrickFormType;
use App\Form\VideoFormType;
use App\Repository\FigureRepository;
use App\Repository\ImageRepository;
use App\Repository\MessageRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminTricksController extends AbstractController
{
    /**
     * @Route("/tricks/new", name="admin_tricks_new")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
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

            $this->addFlash("success","Yes !!! Votre trick à bien été ajouté !! ❄❄❄");
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
    public function editTrick(Figure $figure,$id,Request $request,EntityManagerInterface $entityManager,ImageRepository $imageRepository,Filesystem $filesystem,VideoRepository $videoRepository)
    {
        $form = $this->createForm(TrickFormType::class,$figure,["is_edit"=>true]);
        $imageForm = $this->createForm(ImageFormType::class);
        $videoForm = $this->createForm(VideoFormType::class);

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $this->editImage(uniqid() . $imageForm["image"]->getData()->getClientOriginalName(), $imageForm["image"]->getData(), $imageRepository, $imageForm['idImage']->getData(), $figure, $entityManager);
            return $this->redirectToRoute('admin_tricks_edit',['id'=>$id]);
        }

        $videoForm->handleRequest($request);
        if ($videoForm->isSubmitted() && $videoForm->isValid()) {
            $this->editVideo($videoRepository,$videoForm['id']->getData(),$videoForm['link']->getData(),$figure,$entityManager);
            return $this->redirectToRoute('admin_tricks_edit',['id'=>$id]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->editFigure($form->getData(), $entityManager);
            return $this->redirectToRoute("app_homepage");
        }
        

        return $this->render("admin_tricks/edit.html.twig",
            [
                "trickForm"=>$form->createView(),
                "imageForm" => $imageForm,
                "videoForm" => $videoForm,
                "trick" => $figure,]);
    }

    /**
     * @Route("tricks/delete/image/{id}",name="admin_image_delete")
     */
    public function deletePic(Image $image,$id,ImageRepository $imageRepository,Filesystem $filesystem)
    {
        $trickId = $image->getFigure()->getId();
        $imageRepository->deletePic($id,$filesystem);
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

    public function editImage($newImageName,UploadedFile $uploadedFile,ImageRepository $imageRepository,$idImage,Figure $figure, EntityManagerInterface $entityManager)
    {
        if (!empty($idImage)) {
            $imageRepository->editImage($idImage, $newImageName, $uploadedFile);
            $this->addFlash('success',"L'image été modifiée !");
        }
        else {
            $imageRepository->createImage($uploadedFile,$newImageName,$figure);
            $this->addFlash('success',"L'image été ajoutée !");
        }
        $figure->setModifiedAtNow();
        $entityManager->flush();
    }

    public function editVideo(VideoRepository $videoRepository,$videoId,$link,$figure,EntityManagerInterface $entityManager)
    {
        if (!empty($videoId)) {
            $videoRepository->editVideo($videoId,$link);
            $this->addFlash("success","La vidéo à été modifiée");
        }
        else{
            $videoRepository->createVideo($figure,$link);
            $this->addFlash('success',"Votre vidéo à été ajoutée");
        }
        $figure->setModifiedAtNow();
        $entityManager->flush();
    }

    public function editFigure($newFigure,EntityManagerInterface $entityManager)
    {
        $figure = $newFigure;
        $entityManager->persist($figure);
        $figure->setModifiedAtNow();
        $entityManager->flush();
        $this->addFlash('success',"Le trick à été modifié!");
    }


}
