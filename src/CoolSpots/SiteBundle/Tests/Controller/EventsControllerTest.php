<?php

namespace CoolSpots\SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/events');
    }

    public function testDetails()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/events/details');
    }

}
