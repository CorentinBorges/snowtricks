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

class ImageFixtures extends GlobalFixture implements DependentFixtureInterface
{
    /** @var Generator */
    private $faker;



    private static $randomImages = [
        'jump1.jpg',
        'trick1.jpg',
        'trick2.jpg'];


    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $this->generateRandomPics($manager);
        $this->generateMainPics($manager);
        $manager->flush();
    }

    public function generateMainPics(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $imagesNames = $this->figureNameGenerator();

            if ($i !== 4) {
                $image = new Image();
                $image
                    ->setName($imagesNames[$i])
                    ->setFigure($this->getReference(Figure::class . '_' . $i))
                    ->setAlt($this->faker->sentence($nbWords = 20, $variableNbWords = true))
                    ->setFirst(true);
                $manager->persist($image);
            }
        }
    }

    public function figureNameGenerator()
    {
        $imagesNames = [];
        foreach ($this->figureNames as $figureName) {
            $noSpace=str_replace(' ', '_', $figureName);
            $imagesNames[] = strtolower($noSpace) . ".jpg";
        }
        return $imagesNames;
    }

    public function generateRandomPics(ObjectManager $manager)
    {
        $trickNumber = 0;
        for ($i = 0; $i < 30; $i++) {
            $image = new Image();
            $image
                ->setName($this->faker->randomElement(self::$randomImages))
                ->setFigure($this->getReference(Figure::class . '_' . $trickNumber))
                ->setAlt($this->faker->sentence($nbWords = 20, $variableNbWords = true))
                ->setFirst(false);
            $trickNumber=$trickNumber+1;

            if ($trickNumber==10) {
                $trickNumber = 0;
            }
            $manager->persist($image);
        }
    }

    public function getDependencies()
    {
        return [FigureFixtures::class];
    }
}
