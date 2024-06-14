<?php

namespace quiz;

use PHPUnit\Framework\TestCase;

include '../code/class/Stats.php';

class StatsTest extends TestCase
{
    public function testGetTimesAsked()
    {
        $arrange = new Stats(1,0,0);
        $act = $arrange->getTimesAsked();
        $this->assertEquals(0,$act);
    }
}