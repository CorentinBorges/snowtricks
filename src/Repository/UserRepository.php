<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\User;
use App\Service\AvatarFileUploader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var AvatarFileUploader
     */
    private $fileUploader;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager, Filesystem $filesystem, AvatarFileUploader $fileUploader)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
        $this->filesystem = $filesystem;

        $this->fileUploader = $fileUploader;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }
        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function createUser(User $user,string $role, UserPasswordEncoderInterface $passwordEncoder, FormInterface $form)
    {
        $user
            ->setRoles([$role])
            ->setPassword($passwordEncoder->encodePassword($user,$form['password']->getData()))
            ->setIsValid(false);
        return $user;
    }

    public function editUser(User $user,Request $request)
    {
        $user->setUsername($request->request->get('username'));
        $user->setEmail($request->request->get('email'));
        $this->manager->persist($user);
        $this->manager->flush();

    }

    public function editAvatar(Image $image,$imageFile,User $user)
    {
        if ($user->getAvatarPath()) {
            $this->filesystem->remove('images/avatars/'.$user->getAvatarPath());
        }
        $imageName=$this->fileUploader->upload($imageFile);
        $user->setAvatarPath($imageName);
        $user->setAvatarAlt($image->getAlt());
        $this->manager->persist($user);
        $this->manager->flush();
    }


}
