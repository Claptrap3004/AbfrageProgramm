<?php
require 'public/automateInclude.php';
use PHPUnit\Framework\TestCase;
use quiz\MariaDBConnector;

class MariaDBConnectorTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetConnection()
    {
        $arrange = new MariaDBConnector();
        $act = $arrange->getConnection();
        $this->assertEquals('object', gettype($act));
    }
}
