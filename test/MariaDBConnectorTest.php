<?php

namespace quiz;

use PHPUnit\Framework\TestCase;

include '../code/class/CanHandleDB.php';
include '../code/class/MariaDBConnector.php';
include_once '../code/class/CanConnectDB.php';

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
