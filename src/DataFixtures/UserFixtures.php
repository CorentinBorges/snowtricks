<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var Generator */
    private $faker;



    private static $avatars = [
        'face.jpg',
        'face2.jpg',
        'face3.jpg',
        'face3.jpg',
        'face4.jpg'
    ];
    /**
     * @var PasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($this->faker->name)
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($this->encoder->encodePassword($user,'coucou'))
                ->setEmail($this->faker->email)
                ->setAvatarPath($this->faker->randomElement(self::$avatars));
            $this->setReference(User::class.'_'.$i,$user);
            $manager->persist($user);
        }

        $manager->flush();
    }

}
