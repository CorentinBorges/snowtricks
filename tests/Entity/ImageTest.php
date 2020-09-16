<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * @var Image
     */
    private $image;
    /**
     * @var Figure
     */
    private $figure;

    public function setUp()
    {
        $this->image = new Image();
        $this->figure = new Figure();
    }

    public function testSetAndGetName()
    {
        $this->image->setName('Test');
        $this->assertSame('Test', $this->image->getName());
    }

    public function testSetAndGetFigure()
    {
        $this->image->setFigure($this->figure);
        $this->assertSame($this->figure, $this->image->getFigure());
    }

    public function testSetAndGetFirst()
    {
        $this->image->setFirst(true);
        $this->assertTrue($this->image->getFirst());
    }

    public function testSetAndGetAlt()
    {
        $this->image->setAlt('Alt test text');
        $this->assertSame('Alt test text', $this->image->getAlt());
    }
}
