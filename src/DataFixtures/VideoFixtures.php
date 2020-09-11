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
    /** @var Generator */
    private $faker;

    private static $videos = [
        'https://www.youtube.com/embed/tHHxTHZwFUw',
        'https://www.youtube.com/embed/G9qlTInKbNE',
        'https://www.youtube.com/embed/1MQfbMoCfb4',
        'https://www.youtube.com/embed/AzJPhQdTRQQ',
        'https://www.youtube.com/embed/SQyTWk7OxSI'

    ];

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

    public function getDependencies()
    {
        return [FigureFixtures::class];
    }
}
