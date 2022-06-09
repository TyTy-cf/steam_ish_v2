<?php


namespace App\Tests\Admin;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CountryControllerTest.php
 *
 * @author Kevin Tourret
 */
class CountryControllerTest extends WebTestCase
{

    public function testCountryNewPage()
    {
        $client = static::createClient();
        $client->request('GET', '/country/new');
        $this->assertResponseIsSuccessful('Cannot access form country');
    }

    public function testCountryFormSubmit() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/country/new');

        $countryRepository = $this->getContainer()->get('doctrine')->getRepository('App:Country');
        $alreadyExists = $countryRepository->findOneBy(['code' => 'be']) != null ? 0 : 1;
        $countBefore = sizeof($countryRepository->findAll());

        $form = $crawler->selectButton('Valider')->form();
        $form['country_form[code]'] = 'be';
        $form['country_form[name]'] = 'Belgique';
        $form['country_form[nationality]'] = 'Belge';
        $client->submit($form);

        $this->assertEquals(($countBefore + $alreadyExists), sizeof($countryRepository->findAll()), "New entity hasn't be added");
    }

}
