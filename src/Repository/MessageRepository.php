<?php

namespace App\Repository;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface  $entityManager)
    {
        parent::__construct($registry, Message::class,$entityManager);
    }

    public function deleteMessagesFromTrick($id)
    {
        $messages = $this->findBy(["figure"=>$id]);
        foreach ($messages as $message) {
            $this->entityManager->remove($message);
        }
    }

    public function addMessageDatabase(Message $comment, Figure $figure, User $user)
    {
        $comment
            ->setFigure($figure)
            ->setUser($user)
            ->setCreatedAtNow();
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }

    public function reverseOrder($id)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.figure= :val')
            ->setParameter('val',$id)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function count($id)
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->andWhere('m.figure= :val')
            ->setParameter('val',$id)
            ->getQuery()
            ->getSingleScalarResult();
        
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
