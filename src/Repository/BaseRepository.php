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
        parent::__construct($registry, $class);

        $this->entityManager = $entityManager;
    }

    public function deleteFromDatabase($entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }


}