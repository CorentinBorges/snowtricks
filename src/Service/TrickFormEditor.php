<?php

namespace App\Service;

use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Video;
use App\Repository\FigureRepository;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class TrickFormEditor
{
    public function editFigureTrick(
        FormInterface $form,
        FigureRepository $figureRepository,
        $trickId,
        FileUploader $fileUploader,
        ImageRepository $imageRepository,
        EntityManagerInterface $entityManager
    ) {
            /** @var Figure $figure */
            $figure = $figureRepository->findOneBy(['id' => $trickId]);
            $images = $form->get('images');
            $videos = $form['videos']->getData();

        foreach ($images as $image) {
            $this->editAddImage($image, $fileUploader, $imageRepository, $figure, $entityManager);
        }
        foreach ($videos as $video) {
            /** @var Video $video */
            $video->setFigure($figure);
            $entityManager->persist($video);
        }
            $figure->setModifiedAtNow();
            $entityManager->persist($figure);
            $entityManager->flush();
    }

    public function editAddImage(
        $image,
        FileUploader $fileUploader,
        ImageRepository $imageRepository,
        Figure $figure,
        EntityManagerInterface $entityManager
    ) {
        $imageFile = $image->get('image')->getData();
        $imageFileName = $fileUploader->upload($imageFile);
        $image = $image->getData();
        if ($image->getFirst()) {
            if ($oldImgFirst = $imageRepository->findFirst($figure->getId())) {
                /** @var Image $oldImgFirst */
                $oldImgFirst->setFirst(false);
            }
        }
        /** @var Image $image */
        $image->setName($imageFileName);
        $image->setFigure($figure);
        $entityManager->persist($image);
    }

    public function editChangeImage(
        FormInterface $imageForm,
        FileUploader $fileUploader,
        ImageRepository $imageRepository,
        Figure $figure
    ) {
        /** @var Image $image */
        $image = $imageForm->getData();
        $imageFileName = null;
        if (!empty($imageForm->get('image')->getData())) {
            $imageFile = $imageForm->get('image')->getData();
            $imageFileName = $fileUploader->upload($imageFile);
            $image->setName($imageFileName);
        }
        $imageRepository->editChangeImage($figure, $image);
    }

    public function editChangeVideo(FormInterface $videoForm, VideoRepository $videoRepository, Figure $figure)
    {
        $video = $videoForm->getData();
        $videoId = $videoForm['id']->getData();
        $videoRepository->editVideo($videoId, $video, $figure);
    }
}
