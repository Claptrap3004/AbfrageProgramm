<?php

namespace quiz;

use Exception;
use PDO;

class MariaDBConnector implements CanConnectDB
{
    private string $servername = 'localhost';
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