<?php

namespace App\Tests;

use App\Repository\GameRepository;
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
        $crawlerGlobal = $client->request('GET', '/');

        $crawler = $crawlerGlobal->filter('h2');

        $this->assertCount(4, $crawler, "One title is missing");

        $this->assertStringContainsString( 'Tous les jeux', $crawlerGlobal->text(), 'Title not found');
        $this->assertStringContainsString( 'Les dernières sorties', $crawlerGlobal->text(), 'Title not found');
    }

    public function testAllGames()
    {
        $client = static::createClient();
        $gameRepository = $this->getContainer()->get('doctrine')->getRepository('App:Game');

        $games = $gameRepository->findAll();
        foreach ($games as $game) {
            $client->request('GET', '/game/' . $game->getSlug());
            $this->assertResponseStatusCodeSame(200, 'Error getting the games page');
        }
    }

}
