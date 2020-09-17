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
use App\Service\TrickFormAdder;
use App\Service\TrickFormEditor;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminTricksController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdminTricksController extends AbstractController
{
    /**
     * @Route("/tricks/new", name="admin_tricks_new")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param TrickFormAdder $formRegister
     * @return Response
     */
    public function add(Request $request, TrickFormAdder $formRegister)
    {
        $form = $this->createForm(TrickFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formRegister->trickCreator($form->getData(), $form->get('images'), $form['videos']->getData(), $request);
            return $this->redirectToRoute("app_homepage");
        }

        return $this->render('admin_tricks/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("tricks/delete/{id}",name="admin_tricks_delete")
     * @param ImageRepository $imageRepository
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @param VideoRepository $videoRepository
     * @param FigureRepository $figureRepository
     * @param MessageRepository $messageRepository
     * @param Filesystem $filesystem
     * @return RedirectResponse
     */
    public function deleteTrick(
        ImageRepository $imageRepository,
        $id,
        EntityManagerInterface $entityManager,
        VideoRepository $videoRepository,
        FigureRepository $figureRepository,
        MessageRepository $messageRepository,
        Filesystem $filesystem
    ) {
        $imageRepository->deletePicsFromTrick($id, $filesystem);
        $videoRepository->deleteVideosFromTrick($id);
        $messageRepository->deleteMessagesFromTrick($id);
        $figureRepository->deleteTrick($id);
        $entityManager->flush();
        $this->addFlash('success', 'Le trick à bien été supprimé');
        return $this->redirectToRoute('app_homepage');
    }

    /**
     * @Route("tricks/edit/{id}",name="admin_tricks_edit")
     * @param Figure $figure
     * @param $id
     * @param FileUploader $fileUploader
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ImageRepository $imageRepository
     * @param FigureRepository $figureRepository
     * @param VideoRepository $videoRepository
     * @return Response
     */
    public function editTrick(
        Figure $figure,
        $id,
        FileUploader $fileUploader,
        Request $request,
        EntityManagerInterface $entityManager,
        ImageRepository $imageRepository,
        FigureRepository $figureRepository,
        VideoRepository $videoRepository
    ) {
        $trickFormEditor = new TrickFormEditor();
        $form = $this->createForm(TrickFormType::class, $figure, ['is_edit' => true]);
        $imageForm = $this->createForm(ImageFormType::class, null, ["is_edit" => true]);
        $videoForm = $this->createForm(VideoFormType::class);

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $trickFormEditor->editChangeImage($imageForm, $fileUploader, $imageRepository, $figure);
            $this->addFlash('success', 'L\'image bien été modifiée !');
            return $this->redirectToRoute('admin_tricks_edit', ['id' => $id]);
        }

        $videoForm->handleRequest($request);
        if ($videoForm->isSubmitted() && $videoForm->isValid()) {
            $trickFormEditor->editChangeVideo($videoForm, $videoRepository, $figure);
            $this->addFlash('success', 'La vidéo bien été modifiée !');
            return $this->redirectToRoute('admin_tricks_edit', ['id' => $id]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $trickFormEditor->editFigureTrick(
                $form,
                $figureRepository,
                $id,
                $fileUploader,
                $imageRepository,
                $entityManager
            );
            $this->addFlash("success", "Votre trick à bien été édité");
            return $this->redirectToRoute('admin_tricks_edit', ['id' => $id]);
        }

        return $this->render(
            "admin_tricks/edit.html.twig",
            [
                "form" => $form->createView(),
                "imageForm" => $imageForm,
                "videoForm" => $videoForm,
            "trick" => $figure,
            ]
        );
    }

    /**
     * @Route("tricks/delete/image/{id}",name="admin_image_delete")
     * @param Image $image
     * @param $id
     * @param ImageRepository $imageRepository
     * @param Filesystem $filesystem
     * @return RedirectResponse
     */
    public function deletePic(Image $image, $id, ImageRepository $imageRepository, Filesystem $filesystem)
    {
        $trickId = $image->getFigure()->getId();
        $imageRepository->deletePic($id, $filesystem);
        $this->addFlash("success", "L'image a été supprimée");
        return $this->redirectToRoute('admin_tricks_edit', ['id' => $trickId]);
    }


    /**
     * @Route("tricks/delete/video/{id}",name="admin_video_delete")
     * @param Video $video
     * @param VideoRepository $videoRepository
     * @return RedirectResponse
     */
    public function deleteVideo(Video $video, VideoRepository $videoRepository)
    {
        $trickId = $video->getFigure()->getId();
        $videoRepository->deleteFromDatabase($video);
        $this->addFlash("success", "La vidéo a été supprimée");
        return $this->redirectToRoute('admin_tricks_edit', ['id' => $trickId]);
    }
}
