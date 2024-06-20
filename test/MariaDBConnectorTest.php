<?php

namespace quiz;

use PHPUnit\Framework\TestCase;

include 'classIncludes.php';
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
