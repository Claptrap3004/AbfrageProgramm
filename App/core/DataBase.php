<?php
namespace quiz;

use PDO;

abstract class DataBase
{

    static protected ?PDO $conn = null;
    static protected ?CanConnectDB $connector = null;
    protected function connect():PDO|null
    {
        if (self::$connector === null) self::$connector = new MariaDBConnector();
        if (self::$conn === null) try {
            self::$conn = self::$connector->getConnection();
        } catch (\Exception $e) {
        }
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