<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\PointOfSale;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger)
    {

    }

    public function load(ObjectManager $manager): void
    {
//        $categories = $manager->getRepository(Category::class)->findAll();

        $this->createProduct($manager);

        $manager->flush();
    }

    public function createProduct( ObjectManager $manager)
    {
        $arrayOfPizza = ["pizza 4 fromages", "pizza napolitaine", "pizza royale", "pizza anchois"];
        $arrayOfCalzone = ["calzone 4 fromages", "calzone napolitaine", "calzone royale", "calzone anchois"];
        $arrayOfDrinks = ["Coca Kola", "Vin rouge", "Jus de fruit", "Eau"];

        $categoryPizz = $manager->getRepository(Category::class)->findBy(['name'=>"pizza"]);
        $categoryCalzone = $manager->getRepository(Category::class)->findBy(['name'=>"calzone"]);
        $categoryDrink = $manager->getRepository(Category::class)->findBy(['name'=>"boisson"]);

        $this->constructProductByCat( $manager, $arrayOfPizza, $categoryPizz);

        $this->constructProductByCat( $manager, $arrayOfCalzone, $categoryCalzone);

        $this->constructProductByCat( $manager, $arrayOfDrinks, $categoryDrink);
    }

    public function constructProductByCat(ObjectManager $manager, $arrayOf, $categories)
    {
        for($i=0; $i<4; $i++)
        {
            $pointofsales = $manager->getRepository(PointOfSale::class)->findAll();
            $faker = Faker\Factory::create('fr_FR');
            $product = new Product();

            $product->setDescription($faker->text());
            $product->setSlug($this->slugger->slug($arrayOf[$i]));

            $product->setPrice($faker->numberBetween(1, 12));

            /*  $product->setCategory($faker->randomElement($array = $categories));*/

            $product->setProductPointofsale($faker->randomElement($array = $pointofsales));

            $product->setCategory($categories[0]);

            $product->setName($arrayOf[$i]);

            $manager->persist($product);
        }
    }

    public function getDependencies(): array
    {

        return [
            PointOfSaleFixtures::class,
            UserFixtures::class
        ];
    }
}
