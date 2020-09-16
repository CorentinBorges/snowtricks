<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{

    /**
     * @var Message
     */
    private $message;
    /**
     * @var Figure
     */
    private $figure;
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        $this->message = new Message();
        $this->figure = new Figure();
        $this->user = new User();
    }

    public function testSetAndGetContent()
    {
        $this->message->setContent('lly encounter consequences that are e');
        $this->assertSame('lly encounter consequences that are e', $this->message->getContent());
    }

    public function testSetAndGetFigure()
    {
        $this->message->setFigure($this->figure);
        $this->assertSame($this->figure, $this->message->getFigure());
    }

    public function testSetAndGetUser()
    {
        $this->message->setUser($this->user);
        $this->assertSame($this->user, $this->message->getUser());
    }

    public function testSetAndGetCreatedAt()
    {
        $date = new \DateTime('now');
        $this->message->setCreatedAt($date);
        $this->assertSame($date, $this->message->getCreatedAt());
    }

    public function testCreatedAtNow()
    {
        $this->message->setCreatedAtNow();
        $this->assertTrue($this->message->getCreatedAt() instanceof \DateTime);
    }
}
