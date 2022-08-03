<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class authorizationTest extends WebTestCase
{
    /*private KernelBrowser $client;
    private UserRepository|null $userRepository;*/

    protected function setUp(): void{
        /*$this->client = static::createClient();
        $container = static::getContainer();
        $this->userRepository = $container->get(UserRepository::class);*/

    }

    /** @test */
    public function an_admin_can_visit_the_admin(){
        $testUser = $this->userRepository->findOneBy(['email' => 'szyszkoweklocki@gmail.com']);

        $this->client->loginUser($testUser);

        $this->client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();
    }
}