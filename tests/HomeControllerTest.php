<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{

    public function testHomePageAccess()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(200, 'Error getting the home page');
    }



    public function testHomePageTitle()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $crawler = $crawler->filter('h2');

        $this->assertCount(4, $crawler, "One title is missing");

        $this->assertStringContainsString($crawler->eq(0)->text(), 'Tous les jeux','Title not found');
    }


}
