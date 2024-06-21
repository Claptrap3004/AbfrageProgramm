<?php
require 'public/automateInclude.php';
use PHPUnit\Framework\TestCase;
use quiz\IdText;
use quiz\KindOf;
use quiz\MariaDBConnector;

class IdTextTest extends TestCase
{
    public function testGetId()
    {
        $arrange = new IdText(1,'ICMP', KindOf::ANSWER, new MariaDBConnector());
        $act = $arrange->getText();
        $this->assertEquals('ICMP', $act);
    }
}