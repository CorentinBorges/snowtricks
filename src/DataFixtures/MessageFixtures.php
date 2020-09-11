<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var Generator */
    private $faker;

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 200; $i++) {
            $message = new Message();
            $message
                ->setContent($this->faker->text(200))
                ->setFigure($this->getReference(Figure::class . '_' . $this->faker->numberBetween(0, 9)))
                ->setUser($this->getReference(User::class . '_' . $this->faker->numberBetween(0, 9)))
                ->setCreatedAt($this->faker->dateTimeBetween("-300 days", "-1 days"));
            $manager->persist($message);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [FigureFixtures::class,
            UserFixtures::class];
    }
}
