<?php

namespace quiz;

use PHPUnit\Framework\TestCase;

include '../code/class/Stats.php';

include '../code/class/MariaDBConnector.php';

class StatsTest extends TestCase
{
    public function testGetTimesAsked()
    {

        $arrange = new Stats(1,2,1, new MariaDBConnector());
        $act = $arrange->getTimesAsked();
        $this->assertEquals(0,$act);
    }
}
