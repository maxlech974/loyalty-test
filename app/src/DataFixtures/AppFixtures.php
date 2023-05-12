<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('Test')
            ->setFirstName('User')
            ->setEmail('test.user@example.com')
            ->setBirthDate(new \DateTime('1980-01-01'));

        $manager->persist($user);
        $manager->flush();

        $manager->flush();
    }
}
