<?php


namespace App\Repository;


use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

     public function __construct(ManagerRegistry $registry,$class, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, $class);//todo: se passer de cette ligne? Karim

        $this->entityManager = $entityManager;
    }
}