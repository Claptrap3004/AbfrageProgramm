<?php

namespace quiz;
require __DIR__ . '/../vendor/autoload.php';
include '../code/class/IdText.php';


class IdTextTest extends \PHPUnit\Framework\TestCase
{
    public function testGetId()
    {
        $arrange = new namespace\IdText(1,'ICMP', KindOfIdText::ANSWER);
        $act = $arrange->getIdentifier();
        $this->assertEquals('ICMP', $act);
    }
}