<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\Entity\Contact
 */
class ContactTest extends TestCase
{
    public function testSetFirstName()
    {
        $firstName = 'Ahmed';
        $contact = new Contact();
        $contact->setFirstName($firstName);

        static::assertSame($firstName, $contact->getFirstName());
    }

    public function testSetLastName()
    {
        $lastName = 'Sajjad';
        $contact = new Contact();
        $contact->setLastName($lastName);

        static::assertSame($lastName, $contact->getLastName());
    }

    public function testSetAddress()
    {
        $address = 'nadeem park';
        $contact = new Contact();
        $contact->setAddress($address);

        static::assertSame($address, $contact->getAddress());
    }

    public function testSetEmail()
    {
        $email = 'ahmedsajjad@gmail.com';
        $contact = new Contact();
        $contact->setEmail($email);

        static::assertSame($email, $contact->getEmail());
    }

    public function testBirthday()
    {
        $birthday = new \DateTime('03-02-1995');
        $contact = new Contact();
        $contact->setBirthday($birthday);

        static::assertSame($birthday, $contact->getBirthday());
    }
}
