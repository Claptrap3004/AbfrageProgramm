<?php

use quiz\MariaDBConnector;
include_once '../code/class/CanConnectDB.php';
include_once '../code/class/MariaDBConnector.php';
/**
 * @throws Exception
 */
function executeSQLScript(string $filename)
{
    $connector = new MariaDBConnector('test');
    $conn = $connector->getConnection();
    $query = '';
    $sqlScript = file($filename);
    foreach ($sqlScript as $line) {

        $startWith = substr(trim($line), 0, 2);
        $endWith = substr(trim($line), -1, 1);

        if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
            continue;
        }

        $query = $query . $line;
        if ($endWith == ';') {
            $conn->query($query);
            $query = '';
        }
    }
}

try {
    executeSQLScript('testDBCreate.sql');
    executeSQLScript('testDBPop.sql');
} catch (Exception $e) {
}
