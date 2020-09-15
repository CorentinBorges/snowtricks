<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TricksControllerTest extends WebTestCase
{
    public function testRedirectTo()
    {
        $client = static:: createClient();
        $client->request('GET', '/');
        $this->assertEquals(500,$client->getResponse()->getStatusCode());
    }
}