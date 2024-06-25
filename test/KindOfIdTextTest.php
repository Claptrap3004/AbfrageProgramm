<?php
require 'public/automateInclude.php';

use PHPUnit\Framework\TestCase;

class KindOfIdTextTest extends TestCase
{
    public function testGetTableName()
    {
        $arrange = KindOf::ANSWER;
        $result = $arrange->getTableName();
        $this->assertEquals('answer',$result);
    }

    public function testGetName()
    {
        $arrange = KindOf::ANSWER;
        $result = $arrange->getName();
        $this->assertEquals('ANSWER',$result);
    }
}
