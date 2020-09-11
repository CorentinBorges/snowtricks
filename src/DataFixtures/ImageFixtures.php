<?php

/**
 * Images Fixtures
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
use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class FigureFixtures
 *
 * @category Fixtures
 * @package  App\DataFixtures
 * @author   Corentin Borges <corentin1309@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/CorentinBorges/snowtricks
 */
class ImageFixtures extends GlobalFixture implements DependentFixtureInterface
{
    /**
     * Define faker var
     *
     * @var Generator $_faker
     */
    private $_faker;

    /**
     * Array of randome pictures
     *
     * @var string[]
     */
    private static $_randomImages = [
        'jump1.jpg',
        'jump2.jpg',
        'jump3.jpg',
        'jump4.jpg',
        'jump5.jpg',
        'trick1.jpg',
        'trick2.jpg'
    ];

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
        $this->generateRandomPics($manager);
        $this->generateMainPics($manager);
        $manager->flush();
    }

    /**
     * Generate maine pics
     *
     * @param ObjectManager $manager Load ObjectManager
     *
     * @return void
     */
    public function generateMainPics(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $imagesNames = $this->figureNameGenerator();

            if ($i !== 4) {
                $image = new Image();
                $image
                    ->setName($imagesNames[$i])
                    ->setFigure($this->getReference(Figure::class . '_' . $i))
                    ->setAlt($this->_faker->sentence($nbWords = 20, $variableNbWords = true))
                    ->setFirst(true);
                $manager->persist($image);
            }
        }
    }

    /**
     * Generate pictures name
     *
     * @return array
     */
    public function figureNameGenerator()
    {
        $imagesNames = [];
        foreach ($this->figureNames as $figureName) {
            $noSpace=str_replace(' ', '_', $figureName);
            $imagesNames[] = strtolower($noSpace) . ".jpg";
        }
        return $imagesNames;
    }

    /**
     * Generate random pictures
     *
     * @param ObjectManager $manager Load ObjectManager
     *
     * @return void
     */
    public function generateRandomPics(ObjectManager $manager)
    {
        $trickNumber = 0;
        for ($i = 0; $i < 30; $i++) {
            $image = new Image();
            $image
                ->setName($this->_faker->randomElement(self::$_randomImages))
                ->setFigure($this->getReference(Figure::class . '_' . $trickNumber))
                ->setAlt($this->_faker->sentence($nbWords = 20, $variableNbWords = true))
                ->setFirst(false);
            $trickNumber=$trickNumber+1;

            if ($trickNumber==10) {
                $trickNumber = 0;
            }
            $manager->persist($image);
        }
    }


    /**
     * Load firsts fixtures
     *
     * @return string[]
     */
    public function getDependencies()
    {
        return [FigureFixtures::class];
    }
}
