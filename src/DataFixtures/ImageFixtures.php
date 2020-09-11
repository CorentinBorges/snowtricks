<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Image;
use App\Repository\ImageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var Generator */
    private $faker;

    private static $tricksName = [
        'mute.jpg',
        'trick1.jpg',
        'trick2.jpg'];

    private $firstExist = [];

    private $imageRepository;

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $trickNumber = 0;
        for ($i = 0; $i < 30; $i++) {

            $image = new Image();
            $image->setName($this->faker->randomElement(self::$tricksName));
            $image->setFigure($this->getReference(Figure::class . '_' . $trickNumber));
            if (in_array($image->getFigure()->getId(),$this->firstExist)) {
                $image->setFirst(false);
            }
            else{
                $image->setFirst(true);
                $this->firstExist[] = $image->getFigure()->getId();
            }
            $trickNumber=$trickNumber+1;

            if ($trickNumber==10) {
                $trickNumber = 0;
            }
            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [FigureFixtures::class];
    }
}
