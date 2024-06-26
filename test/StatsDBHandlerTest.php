<?php
require 'public/automateInclude.php';

use PHPUnit\Framework\TestCase;
use quiz\CanConnectDB;
use quiz\MariaDBConnector;
use quiz\StatsDBHandler;

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
