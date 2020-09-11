<?php

/**
 * User Fixtures
 * PHP version 7.4
 *
 * @category Fixtures
 * @package  App\DataFixtures
 * @author   Corentin Borges <corentin1309@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/CorentinBorges/snowtricks
 */
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
    private $_faker;



    private static $_avatars = [
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
    private $_encoder;


    /**
     * Load from fixture
     *
     * @param UserPasswordEncoderInterface $encoder encode user passwords
     *
     * @return void
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->_encoder = $encoder;
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
        $this->_faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user
                ->setUsername($this->_faker->name)
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($this->_encoder->encodePassword($user, 'snowPass'))
                ->setEmail($this->_faker->email)
                ->setAvatarPath($this->_faker->randomElement(self::$_avatars))
                ->setAvatarAlt($this->_faker->sentence($nbWords = 20, $variableNbWords = true))
                ->setIsValid(true);

            $this->setReference(User::class.'_'.$i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

}
