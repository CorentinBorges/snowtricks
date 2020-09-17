<?php

/**
 * Global Fixtures
 * PHP version 7.4
 *
 * @category Fixtures
 * @package  App\DataFixtures
 * @author   Corentin Borges <corentin1309@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/CorentinBorges/snowtricks
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class GlobalFixtures
 *
 * @category Fixtures
 * @package  App\DataFixtures
 * @author   Corentin Borges <corentin1309@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/CorentinBorges/snowtricks
 */
abstract class GlobalFixture extends Fixture
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

    protected $figureNames = [
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
}
