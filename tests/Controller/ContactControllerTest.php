<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ContactController
 */
class ContactControllerTest extends WebTestCase
{
    private array $contactData = [
        'json' => [
            'firstName' => 'Ahmed',
            'lastName' => 'Sajjad',
            'email' => 'ahmadsajjad@gmail.com',
            'address' => '135 b nadeem park 54000',
            'phoneNumber' => '345-476-1362',
            'birthday' => '03-02-1955',
            'picture' => 'https://static9.depositphotos.com/1431107/1154/i/950/depositphotos_11542091-stock-photo-sample-stamp.jpg',
        ],
    ];

    public function testGetAllContacts()
    {
        $client = static::createClient();
        $client->request('GET', '/contacts');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSearchContacts()
    {
        $client = static::createClient();
        $client->request('GET', '/contacts?search_term=ahmed');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetContactWithCorrectId()
    {
        $client = static::createClient();
        $client->request('GET', '/contacts/12');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testGetContactWithIncorrectId()
    {
        $client = static::createClient();
        $client->request('GET', '/contacts/100');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteContactWithCorrectId()
    {
        $client = static::createClient();
        $client->request('DELETE', '/contacts/12');

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteContactWithIncorrectId()
    {
        $client = static::createClient();
        $client->request('DELETE', '/contacts/100');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPostContactWithEmptyData()
    {
        $client = static::createClient();
        $client->request('POST', '/contacts', ['json' => []]);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
}
