<?php


namespace App\Tests\Entity;


use App\Entity\Figure;
use App\Entity\Video;

class VideoTest extends \PHPUnit\Framework\TestCase
{
    private $video;
    private $figure;

    public function setUp()
    {
        $this->video = new Video();
        $this->figure = new Figure();
    }

    public function testSetAndGetFigure()
    {
        $this->video->setFigure($this->figure);
        $this->assertSame($this->figure,$this->video->getFigure());
    }

    public function testSetAndGetEmbedLink()
    {
        $this->video->setEmbedLink('https://www.youtube.com/embed/1TJ08caetkw');
        $this->assertSame(Video::YOUTUBE_LINK.'1TJ08caetkw',$this->video->getLink());
    }

    public function testSetAndGetLinkWithGoodDatas()
    {
        $this->video->setLink('https://youtu.be/tHHxTHZwFUw');
        $this->assertSame(Video::YOUTUBE_LINK.'tHHxTHZwFUw',$this->video->getLink());
    }

    public function testSetAndGetLinkWithWrongLink()
    {
        $this->video->setLink('https://www.youtube.com/watch?v=1TJ08caetkw');
        $this->assertNotSame(Video::YOUTUBE_LINK.'1TJ08caetkw',$this->video->getLink());
    }
}