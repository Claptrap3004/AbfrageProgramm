<?php
// Implementation for MariaDB access
namespace quiz;
include_once 'CanConnectDB.php';

use Exception;
use PDO;

class MariaDBConnector implements CanConnectDB
{
    private string $servername = '127.0.0.1:7147';
    private string $username = 'root';
    private string $password = 'root';
    private string $dbname = 'abfrageprogramm';
    private \PDO $connection;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        try {
            $this->connection =  new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
        } catch (Exception $e) {
            throw new Exception('FEHLER : ' . $e->getMessage() . '<br> Datei : ' . $e->getFile() . '<br> Zeile : ' . $e->getLine() . '<br> Trace : ' . $e->getTraceAsString());
        }
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}