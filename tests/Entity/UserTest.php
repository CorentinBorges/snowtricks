<?php

namespace App\Tests\Entity;

use App\Entity\Figure;
use App\Entity\Message;
use App\Entity\Token;
use App\Entity\User;
use App\Entity\Video;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    /**
     * @var User
     */
    private $user;
    /**
     * @var Message
     */
    private $message;
    /**
     * @var TokenTest
     */
    private $token;

    public function setUp()
    {
        $this->user = new User();
        $this->message = new Message();
        $this->token = new Token();
    }

    public function testSetAndGetUsername()
    {
        $this->user->setUsername('John Doe');
        $this->assertSame('John Doe', $this->user->getUsername());
    }

    public function testSetAndGetRoles()
    {
        $this->user->setRoles(['ROLE_TEST']);
        $this->assertContains('ROLE_TEST', $this->user->getRoles(), 'Role not found');
    }

    public function testSetAndGetPassword()
    {
        $this->user->setPassword('password test');
        $this->assertSame('password test', $this->user->getPassword());
    }

    public function testGetAndAddMessage()
    {
        $this->user->addMessage($this->message);
        $this->assertContains($this->message, $this->user->getMessages(), 'Message not found');
    }

    /**
     * @depends testGetAndAddMessage
     */
    public function testRemoveMessage()
    {
        $this->user->addMessage($this->message);
        $this->user->removeMessage($this->message);
        $this->assertNotContains($this->message, $this->user->getMessages(), 'Message has not been removed');
    }

    public function testSetAndGetEmail()
    {
        $this->user->setEmail('mail@test.com');
        $this->assertSame('mail@test.com', $this->user->getEmail());
    }

    public function testSetAndGetAvatarPath()
    {
        $this->user->setAvatarPath('test/path');
        $this->assertSame('test/path', $this->user->getAvatarPath());
    }

    public function testSetAndGetIsValid()
    {
        $this->user->setIsValid(true);
        $this->assertTrue($this->user->getIsValid());
    }

    public function testAddAndGetTokens()
    {
        $this->user->addToken($this->token);
        $this->assertContains($this->token, $this->user->getTokens());
    }

    /**
     * @depends testAddAndGetTokens
     */
    public function testRemoveToken()
    {
        $this->user->addToken($this->token);
        $this->user->removeToken($this->token);
        $this->assertNotContains($this->token, $this->user->getTokens(), 'TokenTest has not been removed');
    }

    public function testSetAndGetAvatarAlt()
    {
        $this->user->setAvatarAlt('test avatar alt');
        $this->assertSame('test avatar alt', $this->user->getAvatarAlt());
    }
}
