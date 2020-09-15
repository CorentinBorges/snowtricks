<?php


namespace App\Service;

use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;


class TrickFormAdder
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * @var RedirectController
     */
    private $redirectController;


    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag, FileUploader $fileUploader,RedirectController $redirectController)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->fileUploader = $fileUploader;
        $this->redirectController = $redirectController;
    }

    public function trickCreator(Figure $figure,FormInterface $images,array $videos,Request $request)
    {
        foreach ($videos as $video) {
            /** @var Video $video */
            $this->entityManager->persist($video);
        }

        foreach ($images as $image) {
            $this->imageCreator($image);
        }

        $figure->setCreatedAtNow();
        $this->entityManager->persist($figure);
        $this->entityManager->flush();
        $this->flashBag->add("success", "Yes !!! Votre trick à bien été ajouté !! ❄❄❄");
    }

    public function imageCreator($imageForm)
    {
        $imageFile=$imageForm->get('image')->getData();
        $imageFileName=$this->fileUploader->upload($imageFile);
        $image=$imageForm->getData();
        /** @var Image $image */
        $image->setName($imageFileName);
        $this->entityManager->persist($image);
    }

}