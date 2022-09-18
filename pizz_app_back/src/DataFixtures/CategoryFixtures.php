<?php

namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

// implements DependentFixtureInterface
class CategoryFixtures extends Fixture
{

    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        $arrayOfCat = ["pizza", "boisson", "calzone"];

        for($i = 0; $i <3; $i ++) {

            $category = new Category();
            $category->setName( $arrayOfCat[$i]);
            $category->setSlug($this->slugger->slug($category->getName())->lower());
            $manager->persist($category);
        }

        $manager->flush();
    }
/*    public function getDependencies(): array
    {

        return [
            PointOfSaleFixtures::class,
        ];
    }*/
}


