<?php

include 'code/class/MariaDBConnector.php';
use quiz\MariaDBConnector;

$conn = new MariaDBConnector();

echo $_SERVER['DOCUMENT_ROOT'] . __DIR__;