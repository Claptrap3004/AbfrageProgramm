<?php

namespace quiz;
require __DIR__ . '/../vendor/autoload.php';
include '../code/class/IdText.php';


class IdTextTest extends \PHPUnit\Framework\TestCase
{
    public function testGetId()
    {
        $arrange = new namespace\IdText(1,'ICMP', KindOf::ANSWER);
        $act = $arrange->getText();
        $this->assertEquals('ICMP', $act);
    }
}