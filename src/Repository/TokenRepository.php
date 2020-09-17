<?php

namespace App\Repository;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Token|null find($id, $lockMode = null, $lockVersion = null)
 * @method Token|null findOneBy(array $criteria, array $orderBy = null)
 * @method Token[]    findAll()
 * @method Token[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Token::class);
        $this->entityManager = $entityManager;
    }

    public function createTokenInDatabase(User $user, Token $token)
    {
        $now = new \DateTime();
        $time = new \DateTime();
        $expireTime = $time->modify('+2 days');
        $token
            ->setName(uniqid() . uniqid())
            ->setUser($user)
            ->setCreatedAt($now)
            ->setExpiredAt($expireTime)
            ->setIsUsed(false);
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}
