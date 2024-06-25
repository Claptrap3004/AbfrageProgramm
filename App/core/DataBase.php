<?php
namespace quiz;

use PDO;

abstract class DataBase
{
    protected string $tablename = 'user';
    static protected ?PDO $conn = null;
    protected function connect():PDO|null
    {
        if (self::$conn === null) self::$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASSWORD);
        return self::$conn;
    }

    /**
     * @param string $sql
     * @param array $data
     * @return DataBase[]|false
     */
    protected function query(string $sql, array $data = []): array|bool
    {
        $conn = $this->connect();
        $stmt = $conn->prepare($sql);
        $check = $stmt->execute($data);
        if ($check){
            $result = $stmt->fetchAll(PDO::FETCH_CLASS,$this->tablename);
            return count($result) > 0 ? $result : true;
        }
        return false;
    }
    public function getLastInsertedId():int
    {
        return self::$conn !== null ? self::$conn->lastInsertId() : -1;
    }

}