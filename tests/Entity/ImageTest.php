<?php


namespace App\Tests\Entity;


use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Video;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * @var Image
     */
    private $image;

    public function setUp()
    {
        $this->image = new Image();
    }

    public function testSetAndGetFirst()
    {
        $this->image->setFirst(true);
        $this->assertTrue($this->image->getFirst());
    }
}