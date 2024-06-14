<?php

namespace quiz;

use PDO;

class IdTextDBHandler implements CanHandleDB
{
    private string $tableName;
    private PDO $connection;

    /**
     * @param KindOfIdText $kindOfIdText
     * @param CanConnectDB $connectDB
     */
    public function __construct(KindOfIdText $kindOfIdText, CanConnectDB $connectDB){

        $this->tableName = $kindOfIdText->getTableName();
        $this->connection = $connectDB->getConnection();

    }


    public function create(array $args): int
    {
        $sql = "INSERT INTO $this->tableName (text) VALUES (:text);";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':text', $args['text']);
        $stmt->execute();
        return $this->connection->lastInsertId();
    }

    public function findById(int $id): array
    {
        $sql = "SELECT * FROM $this->tableName WHERE id = :id;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->tableName;";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(array $args): void
    {
        // TODO: Implement update() method.
    }

    public function deleteAtId(int $id): void
    {
        // TODO: Implement deleteAtId() method.
    }
}