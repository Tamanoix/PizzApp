<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\OrderDetail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OrderDetailFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $orders = $manager->getRepository(Order::class)->findAll();
        $products = $manager->getRepository(Product::class)->findAll();


        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 30; $i++)
        {
            $orderDetails= new OrderDetail();
            $orderDetails->setQuantity($faker->numberBetween(1, 10));

            $orderDetails->setCommand($faker->randomElement($array = $orders));

            $orderDetails->setProduct($faker->randomElement($array = $products));


            $manager->persist($orderDetails);


        }
        $manager->flush();
    }
    public function getDependencies(): array
    {

        return [
            ProductFixtures::class,
            OrderFixtures::class
        ];
    }
}

