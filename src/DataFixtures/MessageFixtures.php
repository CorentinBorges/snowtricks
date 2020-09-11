<?php

/**
 * Message Fixtures
 * PHP version 7.4
 *
 * @category Fixtures
 * @package  App\DataFixtures
 * @author   Corentin Borges <corentin1309@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/CorentinBorges/snowtricks
 */
namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class MessageFixtures
 *
 * @category Fixtures
 * @package  App\DataFixtures
 * @author   Corentin Borges <corentin1309@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/CorentinBorges/snowtricks
 */
class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Define faker var
     *
     * @var Generator $_faker
     */
    private $_faker;

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
        for ($i = 0; $i < 200; $i++) {
            $message = new Message();
            $message
                ->setContent($this->_faker->text(200))
                ->setFigure($this->getReference(Figure::class . '_' . $this->_faker->numberBetween(0, 9)))
                ->setUser($this->getReference(User::class . '_' . $this->_faker->numberBetween(0, 9)))
                ->setCreatedAt($this->_faker->dateTimeBetween("-300 days", "-1 days"));
            $manager->persist($message);
        }
        $manager->flush();
    }

    /**
     * Load firsts fixtures
     *
     * @return string[]
     */
    public function getDependencies()
    {
        return [FigureFixtures::class,
            UserFixtures::class];
    }
}
