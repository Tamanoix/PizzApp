<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\PointOfSale;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $customers = $manager->getRepository(User::class)-> findByRole("ROLE_CUSTOMER");


        $pointofsales = $manager->getRepository(PointOfSale::class)->findAll();


        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++)
        {
            $order = new Order();
            //$order->setReference($faker->unique()->text(40));

            $id = $order->getId() . $i;
            $order->setReference($id);
            //$order->setReference($faker->unique()->text(40));
            $order->setCustomer($faker->randomElement($array = $customers));
            $order->setDeliverer(null);

            $order->setPointOfSale($faker->randomElement($array = $pointofsales));

            $order->setStatus(0);

            $manager->persist($order);


        }
        $manager->flush();
    }
    public function getDependencies(): array
    {

        return [
            UserFixtures::class
        ];
    }
}
