<?php

namespace quiz;
include '../code/class/CanHandleDB.php';
include '../code/class/MariaDBConnector.php';
include '../code/class/IdTextDBHandler.php';
include '../code/class/StatsDBHandler.php';
include '../code/class/KindOf.php';
include_once '../code/class/CanConnectDB.php';
include_once '../code/class/Stats.php';

use PDO;
use PHPUnit\Framework\TestCase;

class StatsDBHandlerTest extends TestCase
{
    private CanConnectDB $connection;

    public function setUp(): void
    {
        $this->connection = new MariaDBConnector();
    }
    public function testFindById()
    {
        $assert = ['id'=>1,'user_id'=>2,'question_id'=>1,'times_asked'=>0,'times_right'=>0];
        $arrange = new StatsDBHandler(KindOf::STATS,$this->connection,2);
        $act = $arrange->findById(1);
        $this->assertEquals($assert,$act);
    }
}
