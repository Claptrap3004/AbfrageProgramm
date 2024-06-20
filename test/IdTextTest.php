<?php

namespace quiz;
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';
include 'classIncludes.php';

class IdTextTest extends TestCase
{
    public function testGetId()
    {
        $arrange = new namespace\IdText(1,'ICMP', KindOf::ANSWER, new MariaDBConnector());
        $act = $arrange->getText();
        $this->assertEquals('ICMP', $act);
    }
}