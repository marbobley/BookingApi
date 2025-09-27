<?php

namespace App\DataFixtures;

use App\Infrastructure\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $reservation = new Reservation();
        $reservation->setUsername('John');
        $reservation->setStartingDate(new \DateTimeImmutable('2024-06-20 14:00:00'));
        $reservation->setMinuteDuration(90);
        $manager->persist($reservation);
        $manager->flush();
    }
}
