<?php

namespace App\DataFixtures;

use App\Entity\PointOfSale;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Provider\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface $slugger
    )
    {

    }

        public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $pointofsales = $manager->getRepository(PointOfSale::class)->findAll();

        //dd($pointofsales);
        $coordonates = [
            0 =>  [
                "lat" => '43.286927',
                "lng" => '5.601624',],
            1 =>  [
                "lat" => '43.288927',
                "lng" => '5.599144',],
            2 =>  [
                "lat" => '43.287936',
                "lng" => '5.599243',],
            3 =>  [
                "lat" => '43.289212',
                "lng" => '5.599455',],
            4 =>  [
                "lat" => '43.289624',
                "lng" => '5.597454',],
            5 =>  [
                "lat" => '43.289674',
                "lng" => '5.597474',],
            6 =>  [
                "lat" => '43.289694',
                "lng" => '5.597494',],
            7 =>  [
                "lat" => '43.289794',
                "lng" => '5.597594',],
            8 =>  [
                "lat" => '43.288624',
                "lng" => '5.597494',],
        ];

        //dd($pointofsales);
        $this->createAdminUser($manager);


        for($i = 1; $i <21; $i ++)
        {
             //  $roles[] = 'ROLE_CUSTOMER';
            $randomCoord = $coordonates[random_int(0, 8)];

            //dd($randomCoord);
            $this->createUser($manager, $faker->unique()->email.random_int(10, 100).'a'.$i.'gmail.com', "ROLE_CUSTOMER", $pos= null,  $status = null, $randomCoord["lat"],  $randomCoord["lng"]);
            $manager->flush();
        }

        for($i = 1; $i <10; $i ++)
        {
            //  $roles[] = 'ROLE_DELIVERER';
            $this->createUser($manager, $faker->unique()->email.random_int(100, 200).$i.'gmail.com', "ROLE_DELIVERER",  $pos=$faker->randomElement($array =$pointofsales), 0);
            $manager->flush();
        }
        for($i = 1; $i <15; $i ++)
        {
            //  $roles[] = 'ROLE_deliver';
            $this->createUser($manager, $faker->unique()->email.random_int(100, 200).$i.'gmail.com', "ROLE_DELIVERER",  $pos= $faker->randomElement($array =$pointofsales), 1);
            $manager->flush();
        }
        for($i = 1; $i <10; $i ++)
        {
            //  $roles[] = 'ROLE_deliver';
            $this->createUser($manager, $faker->unique()->email.random_int(100, 200).$i.'gmail.com', "ROLE_DELIVERER",  $pos= $faker->randomElement($array =$pointofsales), 2);
            $manager->flush();
        }

        for($i = 0; $i <4; $i ++)
        {
            //  $roles[] = 'ROLE_manager';
            $this->createUser($manager, $faker->unique()->email.random_int(200, 300).$i.'gmail.com',"ROLE_MANAGER",  $pos= $pointofsales[$i],null);
            $manager->flush();
        }
    }

    public function createUser(ObjectManager $manager, string $email, string $roles = "ROLE_CUSTOMER",  $pos= null, int $status = null, string $lat = null, string $lng = null)
    {
        // $pos = point of sale

        $user = new User();

        $faker = Faker\Factory::create('fr_FR');

        $user->setEmail($email);
        $user->setLastname($faker->lastName);
        $user->setFirstname($faker->firstName);
        $user->setAddress($faker->streetAddress);


        $user->setZipcode(Address::postcode());
        $user->setCity($faker->city);

        $user->setRoles([$roles]);
        $user->setPhonenumber($faker->text(10));

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'secret')
        );

        $user->setStatus($status);
        $user->setPointOfSale($pos);
        $user->setLatitude($lat);
        $user->setLongitude($lng);
        $manager->persist($user);

    }

    public function createAdminUser(ObjectManager $manager)
    {

        $user = new User();

        $faker = Faker\Factory::create('fr_FR');

        $user->setEmail('admin@gmail.com');
        $user->setLastname('admin');
        $user->setFirstname('admin');
        $user->setAddress($faker->streetAddress);
        $user->setZipcode(Address::postcode());
        $user->setCity($faker->city);

        $user->setRoles(['ROLE_ADMIN']);
        $user->setPhonenumber($faker->text(10));

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'admin')
        );


        $manager->persist($user);
        $manager->flush();

    }

}
