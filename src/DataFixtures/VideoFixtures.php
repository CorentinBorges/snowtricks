<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class VideoFixtures extends Fixture
{
    /**
     * Define faker var
     *
     * @var Generator $faker
     */
    private $faker;

    private static $videos = [
        'https://www.youtube.com/tHHxTHZwFUw',
        'https://www.youtube.com/G9qlTInKbNE',
        'https://www.youtube.com/1MQfbMoCfb4',
        'https://www.youtube.com/AzJPhQdTRQQ',
        'https://www.youtube.com/SQyTWk7OxSI'

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
        $this->faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $video = new Video();
            $video
                ->setLink($this->faker->randomElement(self::$videos))
                ->setFigure($this->getReference(Figure::class . '_' . $this->faker->numberBetween(0, 9)));
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
