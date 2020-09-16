<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Video;
use PHPUnit\Framework\TestCase;

class FigureTest extends TestCase
{
    /**
     * @var Figure
     */
    private $figure;

    /**
     * @var Message
     */
    private $message;

    /**
     * @var Image
     */
    private $image;
    /**
     * @var Video
     */
    private $video;

    public function setUp()
    {
        $this->figure = new Figure();
        $this->message = new Message();
        $this->image = new Image();
        $this->video = new Video();
    }

    public function testSetAnsGetName()
    {
        $this->figure->setName('test');
        $this->assertSame('test', $this->figure->getName());
    }

    public function testSetAnsGetDescription()
    {
        $this->figure->setDescription('ad minima veniam, quis nostrum exercitat');
        $this->assertSame('ad minima veniam, quis nostrum exercitat', $this->figure->getDescription());
    }

    public function testSetAnsGetGroupe()
    {
        $this->figure->setGroupe('test');
        $this->assertSame('test', $this->figure->getGroupe());
    }

    public function testGetAndSetCreatedAt()
    {
        $now = new \DateTime("now");
        $this->figure->setCreatedAt($now);
        $this->assertEquals($now, $this->figure->getCreatedAt());
    }

    public function testGetAndSetModifiedAt()
    {
        $now = new \DateTime("now");
        $this->figure->setModifiedAt($now);
        $this->assertEquals($now, $this->figure->getModifiedAt());
    }

    public function testGetAndAddMessages()
    {
        $this->figure->addMessage($this->message);
        $this->assertContains($this->message, $this->figure->getMessages(), 'Message not found');
    }


    /**
     * @depends testGetAndAddMessages
     *
     */
    public function testRemoveMessage()
    {
        $this->figure->addMessage($this->message);
        $this->figure->removeMessage($this->message);
        $this->assertNotContains($this->message, $this->figure->getMessages(), 'Message has not been removed');
    }

    public function testGetAndAddImages()
    {
        $this->figure->addImage($this->image);
        $this->assertContains($this->image, $this->figure->getImages(), 'Image not found');
    }

    /**
     * @depends testGetAndAddImages
     *
     */
    public function testRemoveImage()
    {
        $this->figure->addImage($this->image);
        $this->figure->removeImage($this->image);
        $this->assertNotContains($this->image, $this->figure->getImages(), 'Image has not been removed');
    }

    public function testGetAndAddVideos()
    {
        $this->figure->addVideo($this->video);
        $this->assertContains($this->video, $this->figure->getVideos(), 'Video not found');
    }

    /**
     * @depends testGetAndAddVideos
     *
     */
    public function testRemoveVideo()
    {
        $this->figure->addVideo($this->video);
        $this->figure->removeVideo($this->video);
        $this->assertNotContains($this->video, $this->figure->getVideos(), 'Video has not been removed');
    }

    /**
     * @depends testGetAndAddImages
     *
     */
    public function testFindFirst()
    {
        $this->image->setFirst(true);
        $this->figure->addImage($this->image);
        $this->assertSame($this->image, $this->figure->findFirst());
    }

    public function testCreatedAtNow()
    {
        $this->figure->setCreatedAtNow();
        $this->assertTrue($this->figure->getCreatedAt() instanceof \DateTime);
    }

    public function testModifiedAtNow()
    {
        $this->figure->setModifiedAtNow();
        $this->assertTrue($this->figure->getModifiedAt() instanceof \DateTime);
    }
}
