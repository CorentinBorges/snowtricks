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
use App\Service\AvatarFileUploader;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminTricksController extends AbstractController
{
    /**
     * @Route("/tricks/new", name="admin_tricks_new")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param AvatarFileUploader $fileUploader
     * @param ImageRepository $imageRepository
     * @param VideoRepository $videoRepository
     * @param FigureRepository $figureRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader)
    {
        $form = $this->createForm(TrickFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Figure $figure */
            $figure = $form->getData();
            $images=$form->get('images');
            $videos = $form['videos']->getData();
            foreach ($videos as $video) {
                /** @var Video $video */
                $entityManager->persist($video);
            }
            foreach ($images as $image) {
                $imageFile=$image->get('image')->getData();
                $imageFileName=$fileUploader->upload($imageFile);
                $image=$image->getData();
                /** @var Image $image */
                $image->setName($imageFileName);
                $entityManager->persist($image);
            }

            $figure->setCreatedAtNow();
            $entityManager->persist($figure);
            $entityManager->flush();
            $this->addFlash("success","Yes !!! Votre trick à bien été ajouté !! ❄❄❄");
            return $this->redirectToRoute('app_homepage');

        }

        return $this->render('admin_tricks/new.html.twig', [
            'form' => $form->createView(),
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
    public function deleteTrick( ImageRepository $imageRepository, $id, EntityManagerInterface $entityManager, VideoRepository $videoRepository, FigureRepository $figureRepository,MessageRepository $messageRepository,Filesystem $filesystem)
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
    public function editTrick(Figure $figure, $id, FileUploader $fileUploader, Request $request, EntityManagerInterface $entityManager, ImageRepository $imageRepository, FigureRepository $figureRepository, VideoRepository $videoRepository)
    {
        $form = $this->createForm(TrickFormType::class,$figure,['is_edit'=>true]);
        $imageForm = $this->createForm(ImageFormType::class,null,["is_edit"=>true]);
        $videoForm = $this->createForm(VideoFormType::class);
        $this->editImageTrick($imageForm,$request,$fileUploader,$imageRepository,$id,$figure);
        $this->editVideoTrick($videoForm, $videoRepository, $figure, $request, $id);
        $this->editFigureTrick($form, $request, $figureRepository, $id, $fileUploader, $imageRepository, $entityManager);

        return $this->render("admin_tricks/edit.html.twig",
            [
                "form"=>$form->createView(),
                "imageForm" => $imageForm,
                "videoForm" => $videoForm,
                "trick" => $figure,]);
    }

    public function editFigureTrick(FormInterface  $form, Request $request, FigureRepository $figureRepository, $trickId, FileUploader $fileUploader, ImageRepository $imageRepository, EntityManagerInterface $entityManager)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Figure $figure */
            $figure = $figureRepository->findOneBy(['id'=>$trickId]);
            $images=$form->get('images');
            $videos = $form['videos']->getData();

            foreach ($images as $image) {
                $imageFile=$image->get('image')->getData();
                $imageFileName=$fileUploader->upload($imageFile);
                $image=$image->getData();
                if ($image->getFirst()) {
                    if ($oldImgFirst=$imageRepository->findFirst($figure->getId())) {
                        /** @var Image $oldImgFirst */
                        $oldImgFirst->setFirst(false);
                    }
                }
                /** @var Image $image */
                $image->setName($imageFileName);
                $image->setFigure($figure);
                $entityManager->persist($image);
            }
            foreach ($videos as $video) {
                /** @var Video $video */
                $video->setFigure($figure);
                $entityManager->persist($video);
            }
            $figure->setModifiedAtNow();
            $entityManager->persist($figure);
            $entityManager->flush();
            $this->addFlash("success","Votre trick à bien été édité");
            return $this->redirectToRoute('admin_tricks_edit', ['id' => $trickId]);
        }
    }

    public function editImageTrick(FormInterface $imageForm, Request $request, FileUploader  $fileUploader, ImageRepository $imageRepository, $trickId, Figure $figure)
    {
        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            /** @var Image $image */
            $image = $imageForm->getData();
            $imageFileName = null;
            if (!empty($imageForm->get('image')->getData())) {
                $imageFile=$imageForm->get('image')->getData();
                $imageFileName=$fileUploader->upload($imageFile);
                $image->setName($imageFileName);
            }
            $imageRepository->editImage($figure,$image);
            $this->addFlash('success', 'L\'image bien été modifiée !');
            return $this->redirectToRoute('admin_tricks_edit', ['id' => $trickId]);
        }
    }

    public function editVideoTrick(FormInterface $videoForm,VideoRepository $videoRepository,Figure $figure,Request $request,$trickId)
    {
        $videoForm->handleRequest($request);
        if ($videoForm->isSubmitted() && $videoForm->isValid()) {
            $video = $videoForm->getData();
            $videoId=$videoForm['id']->getData();
            $videoRepository->editVideo($videoId,$video,$figure);
            $this->addFlash('success', 'La vidéo bien été modifiée !');
            return $this->redirectToRoute('admin_tricks_edit', ['id' => $trickId]);
        }
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
}
