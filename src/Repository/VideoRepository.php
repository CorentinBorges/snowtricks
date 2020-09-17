<?php

namespace App\Repository;

use App\Entity\Figure;
use App\Entity\Video;
use App\Form\TrickFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends BaseRepository
{
    private const YOUTUBE_LINK = "https://www.youtube.com/embed/";

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Video::class, $entityManager);
    }

    public function editVideo(int $videoId, Video $newVideo, Figure $figure)
    {
        $video = $this->findOneBy(["id" => $videoId]);
        $video->setEmbedLink($newVideo->getLink());
        $this->entityManager->persist($video);
        $this->entityManager->flush();
    }

    public function deleteVideosFromTrick($trickId)
    {
        $tricksVideos = $this->findBy(["figure" => $trickId]);
        foreach ($tricksVideos as $tricksVideo) {
            $this->entityManager->remove($tricksVideo);
        }
    }

    public function createLink($link)
    {
        $linkArray = preg_split('#/#', $link);
        $linkCode = $linkArray[3];
        return self::YOUTUBE_LINK . $linkCode;
    }

    // /**
    //  * @return Video[] Returns an array of Video objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Video
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
