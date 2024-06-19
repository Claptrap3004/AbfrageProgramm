<?php
// Implementation for MariaDB access
// if not working in test environment maybe the port needs to be changed. See ddev describe
namespace quiz;
include_once 'CanConnectDB.php';

use Exception;
use PDO;

class MariaDBConnector implements CanConnectDB
{
    private string $servername = '127.0.0.1:6862';
    private string $username = 'root';
    private string $password = 'root';
    private string $dbname = 'abfrageprogramm';
    private static ?\PDO $connection = null;


    public function __construct()
    {

    }

    /**
     * @throws Exception
     */
    public function getConnection(): \PDO
    {
        if (!self::$connection){
            try {
                self::$connection = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            } catch (Exception $e) {
                throw new Exception('FEHLER : ' . $e->getMessage() . '<br> Datei : ' . $e->getFile() . '<br> Zeile : ' . $e->getLine() . '<br> Trace : ' . $e->getTraceAsString());
            }

        }
        return self::$connection;
    }
}