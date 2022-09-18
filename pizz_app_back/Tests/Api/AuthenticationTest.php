<?php
// tests/AuthenticationTest.php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
/*use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;*/

class AuthenticationTest extends ApiTestCase
{
    //use ReloadDatabaseTrait;

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function testLogin(): void
    {
        $client = static::createClient();
        $container = self::getContainer();

       $user = new User();
        $user->setEmail('testz@example.com');
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, 'sigdufvkgequHIHIH!13')
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/api/login_check', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => 'testz@example.com',
                'password' => 'sigdufvkgequHIHIH!13',
            ], ]);


        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

       /* // test not authorized
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/users', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();*/
    }

    public function testLoginWithBaValues(): void
    {
        $client = static::createClient();
        $container = self::getContainer();

        /* $user = new User();
         $user->setEmail('test@example.com');
         $user->setPassword(
             $container->get('security.user_password_hasher')->hashPassword($user, '$3CR3T')
         );

         $manager = $container->get('doctrine')->getManager();
         $manager->persist($user);
         $manager->flush();
        */
        // retrieve a token
        $response = $client->request('POST', '/api/login_check', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => 'dleroux@joseph.com38a2gmail.com',
                'password'=> 'dgsdghdgfhdfgs'
                           ], ]);


        //$json = $response->toArray();
        $this->assertEquals(401 , $response->getStatusCode());


        /* // test not authorized
         $client->request('GET', '/users');
         $this->assertResponseStatusCodeSame(401);

         // test authorized
         $client->request('GET', '/users', ['auth_bearer' => $json['token']]);
         $this->assertResponseIsSuccessful();*/
    }
}