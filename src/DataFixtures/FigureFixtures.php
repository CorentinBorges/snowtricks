<?php

/**
 * Figure Fixtures
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
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class FigureFixtures extends GlobalFixture
{
    /**
     * Define _faker var
     *
     * @var Generator $faker
     */
    protected $faker;

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
        for ($i = 0; $i < 10; $i++) {
            $figure = new Figure();
            $figure
                ->setName($this->figureNames[$i])
                ->setDescription($this->faker->paragraph($nbSentences = 20, $variableNbSentences = true))
                ->setGroupe($this->groupes[$i])
                ->setCreatedAt($this->faker->dateTimeBetween("-100 days", "-80 days"))
                ->setModifiedAt($this->faker->dateTimeBetween("-10 days", "-1 days"));
            $this->addReference(Figure::class . '_' . $i, $figure);
            $manager->persist($figure);
        }
        $manager->flush();
    }
}
