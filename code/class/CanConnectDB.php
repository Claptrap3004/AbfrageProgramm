<?php

namespace quiz;

interface CanConnectDB
{
    public function getConnection():\PDO;
}