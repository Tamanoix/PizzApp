<?php


namespace App\Tests\ServiceClass;

use App\ServiceClass\Tsp;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TspTest extends KernelTestCase
{
    public function distanceTest()
    {

        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $tsp = $container->get(Tsp::class);

      /*  $result = $tsp->distance('44.289212', '5.599455','45.289213', '5.599457');

        // https://www.nhc.noaa.gov/gccalc.shtml to calculate distance online => 60 miles

        $this->assertEquals(60, $result);*/


        $tsp->add('pointOfSale', '5.599455','44.289212',    );
        $tsp->add(1, '5.599456','44.289214',    );
        $tsp->add(2, '5.599476', '44.289234',   );
        $result = $tsp->add(3,   '5.599466', '44.289244',  );

        $this->assertEquals([
            "pointOfSale"=> [
                "longitude"=> '5.599455',
                "latitude"=> '44.289212',
            ],
            1 => [
                "longitude"=> '5.599456',
                "latitude"=> '44.289214',
            ],
           2 => [
                "longitude"=> '5.599476',
                "latitude"=> '44.289234',
            ],
            3=> [
                "longitude"=> '5.599466',
                "latitude"=> '44.289244',
            ]

        ], $result);


    }
}