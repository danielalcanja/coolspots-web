<?php

namespace CoolSpots\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testOauth()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login/auth');
    }

}
