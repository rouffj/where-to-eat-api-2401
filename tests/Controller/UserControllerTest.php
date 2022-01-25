<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRestaurantList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/restaurants');

        $this->assertResponseIsSuccessful();
        $restaurants = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertCount(3, $restaurants);
        $this->assertEquals('Hoki Sushi', $restaurants[0]['name']);
        //$this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testUserCreate()
    {
        $client = static::createClient();
        $client->request('POST', '/users', [], [], [], json_encode([
            'firstName' => 'Joseph3',
            'lastName' => 'titi',
            'email' => 'joseph3@gmail.com',
            'password' => 'testtest',
        ]));

        $this->assertResponseIsSuccessful();
        /** @var UserRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'joseph3@gmail.com']);
        $this->assertEquals('joseph3@gmail.com', $user->getEmail());
    }
}
