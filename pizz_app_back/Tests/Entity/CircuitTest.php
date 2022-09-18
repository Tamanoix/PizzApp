<?php


namespace App\Tests\Entity;

use App\Entity\Circuit;
use PHPUnit\Framework\TestCase;

class CircuitTest extends TestCase
{
    public function testCoords()
    {
        $circuit = new Circuit();
        $coords = [500, 501, -514];

        $circuit->setCoords($coords);
        $this->assertEquals([500, 501, -514], $circuit->getCoords());
    }
}