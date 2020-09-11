<?php

/**
 * Video Fixtures
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
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class VideoFixtures
 *
 * @category Fixtures
 * @package  App\DataFixtures
 * @author   Corentin Borges <corentin1309@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/CorentinBorges/snowtricks
 */
class VideoFixtures extends Fixture
{
    /**
     * Define faker var
     *
     * @var Generator $_faker
     */
    private $_faker;

    private static $_videos = [
        'https://www.youtube.com/embed/tHHxTHZwFUw',
        'https://www.youtube.com/embed/G9qlTInKbNE',
        'https://www.youtube.com/embed/1MQfbMoCfb4',
        'https://www.youtube.com/embed/AzJPhQdTRQQ',
        'https://www.youtube.com/embed/SQyTWk7OxSI'

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
        for ($i = 0; $i < 20; $i++) {
            $video = new Video();
            $video
                ->setLink($this->_faker->randomElement(self::$_videos))
                ->setFigure($this->getReference(Figure::class . '_' . $this->_faker->numberBetween(0, 9)));
            $manager->persist($video);
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
        return [FigureFixtures::class];
    }
}
