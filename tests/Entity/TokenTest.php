<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use App\Entity\Token;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    /**
     * @var Figure
     */
    private $token;
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        $this->token = new Token();
        $this->user = new User();
    }

    public function testSetAnsGetIsUsed()
    {
        $this->token->setisUsed(true);
        $this->assertTrue($this->token->getisUsed());
    }

    public function testSetAndGetUser()
    {
        $this->token->setUser($this->user);
        $this->assertSame($this->user, $this->token->getUser());
    }

    public function testSetAndGetCreatedAt()
    {
        $date = new \DateTime('now');
        $this->token->setCreatedAt($date);
        $this->assertSame($date, $this->token->getCreatedAt());
    }

    public function testSetAndGetExpiredAt()
    {
        $date = new \DateTime('now');
        $this->token->setExpiredAt($date);
        $this->assertSame($date, $this->token->getExpiredAt());
    }

    public function testSetAndGetName()
    {
        $this->token->setName('testName165486');
        $this->assertSame('testName165486', $this->token->getName());
    }
}
