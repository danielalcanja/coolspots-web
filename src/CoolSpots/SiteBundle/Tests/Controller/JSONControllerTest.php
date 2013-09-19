<?php

namespace CoolSpots\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JSONControllerTest extends WebTestCase
{
    public function testLocation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/location');
    }

}
