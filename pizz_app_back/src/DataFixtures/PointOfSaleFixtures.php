<?php

namespace App\DataFixtures;

use App\Entity\PointOfSale;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Provider\Address;
use Faker;

class PointOfSaleFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {

        // coordonates for customer
        $coordonates = [
            0 =>  [
                "lat" => 43.288576,
                "lng" => 5.600254,],
            1 =>  [
                "lat" => 43.289392,
                "lng" => 5.596323,],
            2 =>  [
                "lat" => 43.28848,
                "lng" => 5.600187,],
            3 =>  [
                "lat" => 43.289283,
                "lng" => 5.601409,],
            4 =>  [

                "lat" => 43.287595,
                "lng" => 5.603954,],
            5 =>  [
                "lat" => 43.287595,
                "lng" => 5.603954,],


        ];


        for($i = 1; $i <5; $i ++) {

            $faker = Faker\Factory::create('fr_FR');

            $pointOfSale = new PointOfSale();
            $pointOfSale->setName($faker->text(15));
            $pointOfSale->setAddress($faker->text());
            $pointOfSale->setZipcode(Address::postcode());
            $pointOfSale->setCity($faker->text());
            $pointOfSale->setLatitude($coordonates[$i]['lat']);
            $pointOfSale->setLongitude($coordonates[$i]['lng']);


            $manager->persist($pointOfSale);
            $manager->flush();
        }

        $manager->flush();
    }
}
