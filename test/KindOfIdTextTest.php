<?php

namespace quiz;
include '../code/class/KindOfIdText.php';

use PHPUnit\Framework\TestCase;

class KindOfIdTextTest extends TestCase
{
    public function testGetTableName()
    {
        $arrange = KindOfIdText::ANSWER;
        $result = $arrange->getTableName();
        $this->assertEquals('answer',$result);
    }

    public function testGetName()
    {
        $arrange = KindOfIdText::ANSWER;
        $result = $arrange->getName();
        $this->assertEquals('ANSWER',$result);
    }
}
