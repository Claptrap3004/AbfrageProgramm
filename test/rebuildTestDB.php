<?php


include 'public/automateInclude.php';
use quiz\MariaDBConnector;

/**
 * @throws Exception
 */
function executeSQLScript(string $filename): void
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
    executeSQLScript('test/testDBCreate.sql');
    executeSQLScript('test/testDBPop.sql');
} catch (Exception $e) {
}
