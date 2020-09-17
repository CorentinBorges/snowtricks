<?php

namespace App\Repository;

use App\Entity\Figure;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Figure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Figure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Figure[]    findAll()
 * @method Figure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Figure::class, $entityManager);
    }

    public function findFirst($id)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.images', 'i')
            ->addSelect('i')
            ->andWhere('i.first = 1')
            ->andWhere('f.id= :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult();
    }

    public function createFigure($name, $description, $groupe): Figure
    {
            $figure = new Figure();

        $figure
            ->setName($name)
            ->setDescription($description)
            ->setGroupe($groupe)
            ->setCreatedAtNow();
        $this->entityManager->persist($figure);
        return $figure;
    }

    public function deleteTrick($id)
    {
        $trick = $this->findOneBy(["id" => $id]);
        $this->entityManager->remove($trick);
    }
}
