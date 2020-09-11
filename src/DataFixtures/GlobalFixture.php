<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;

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

    protected $figureNames=[
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