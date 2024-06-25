<?php
require 'public/automateInclude.php';

use PHPUnit\Framework\TestCase;
use quiz\CanConnectDB;
use quiz\IdTextDBHandler;
use quiz\MariaDBConnector;


class IdTextDBHandlerTest extends TestCase
{
    private CanConnectDB $db;
    protected function setUp(): void
    {
        parent::setUp();
        $this->db = new MariaDBConnector();
    }

    public function testCreate()
    {
        $arrange = new IdTextDBHandler(KindOf::CATEGORY,$this->db);
        $act = $arrange->create(['text' => 'newCat']);
        $this->assertEquals(7, $act);

    }

    public function testDelete()
    {
        $arrange = new IdTextDBHandler(KindOf::ANSWER,$this->db);
        $act = $arrange->deleteAtId(13);
        $this->assertTrue($act);
    }


}
