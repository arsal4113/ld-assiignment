<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ContactFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 12; ++$i) {
            $faker = Factory::create('en_US');

            $contact = new Contact();
            $contact->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setAddress($faker->address)
                ->setPhoneNumber($faker->phoneNumber)
                ->setBirthday($faker->dateTime)
                ->setPicture($faker->imageUrl());

            $manager->persist($contact);
        }
        $manager->flush();
    }
}
