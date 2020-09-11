<?php

namespace App\DataFixtures;

use App\Entity\Figure;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class FigureFixtures extends GlobalFixture
{
    protected $groupes = [
        'Grabs',
        'Rotation',
        'Old school',
        'Flips',
        'Rotation',
        'Rotation',
        'Grabs',
        'Old school',
        'Slide',
        'Grabs'
    ];

    protected $names=[
        'Mute',
        '1080',
        'Backside air',
        'Front Flip',
        '360',
        '540',
        'Stalefish',
        'Japan air',
        'Slide',
        'Indy'
    ];

    /** @var Generator **/
    protected $faker;

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $figure = new Figure();
            $figure
                ->setName($this->names[$i])
                ->setDescription($this->faker->paragraph($nbSentences = 20, $variableNbSentences = true))
                ->setGroupe($this->groupes[$i])
                ->setCreatedAt($this->faker->dateTimeBetween("-100 days", "-80 days"))
                ->setModifiedAt($this->faker->dateTimeBetween("-10 days", "-1 days"));
            $this->addReference(Figure::class.'_'.$i,$figure);
            $manager->persist($figure);
        }

        $manager->flush();
    }
}
