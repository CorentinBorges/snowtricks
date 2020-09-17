<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures
 *
 * @category Fixtures
 * @package  App\DataFixtures
 * @author   Corentin Borges <corentin1309@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/CorentinBorges/snowtricks
 */
class UserFixtures extends Fixture
{
    /**
     * Define faker var
     *
     * @var Generator $_faker
     */
    private $faker;



    private static $avatars = [
        'face.jpg',
        'face2.jpg',
        'face3.jpg',
        'face3.jpg',
        'face4.jpg'
    ];

    /**
     * Password encoder
     *
     * @var PasswordEncoderInterface
     */
    private $encoder;


    /**
     * Load from fixture
     *
     * @param UserPasswordEncoderInterface $encoder encode user passwords
     *
     * @return void
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load from fixture
     *
     * @param ObjectManager $manager Load ObjectManager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user
                ->setUsername($this->faker->name)
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($this->encoder->encodePassword($user, 'snowPass'))
                ->setEmail($this->faker->email)
                ->setAvatarPath($this->faker->randomElement(self::$avatars))
                ->setAvatarAlt($this->faker->sentence($nbWords = 20, $variableNbWords = true))
                ->setIsValid(true);

            $this->setReference(User::class . '_' . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
