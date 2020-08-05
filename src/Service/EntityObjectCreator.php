<?php


namespace App\Service;


use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\TrickFormType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EntityObjectCreator
{


    /**
     * @var VideoRepository
     */
    private $videoRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(VideoRepository $videoRepository, EntityManagerInterface $entityManager)
    {
        $this->videoRepository = $videoRepository;
        $this->entityManager = $entityManager;
    }

    /*todo: delete class*/


}