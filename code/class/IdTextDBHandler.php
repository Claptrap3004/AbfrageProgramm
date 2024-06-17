<?php
// provides CRUD functionality for all IdText - Objects, all objects that derive from IdText class are going to have
// CanHandleDB - Interface - Implementations that derive from this class because read and delete operations always work
// the same way, only  update and create operations need to be implemented by polymorphism
// As specified in the Interface the arrays as parameters are generell, validation of the content ist happening in the
// implementations of the interface. Therefor the two validation methods (validateCreate and validateUpdate) need to
// be overwritten (Polymorphism again).

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
    public function __construct(KindOfIdText $kindOfIdText, CanConnectDB $connectDB)
    {

        $this->tableName = $kindOfIdText->getTableName();
        $this->connection = $connectDB->getConnection();

    }


    public function create(array $args): int
    {
        if ($this->validateArgsCreate($args)){
        $sql = "INSERT INTO $this->tableName (text) VALUES (:text);";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':text', $args['text']);
        $stmt->execute();
        return $this->connection->lastInsertId();
        }
        return -1;
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

    public function update(array $args): bool
    {
        if ($this->validateArgsUpdate($args)) {
            $sql = "UPDATE $this->tableName SET text = :text WHERE id = :id;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $args['id']);
            $stmt->bindParam(':text', $args['text']);
            return $stmt->execute();
        }
        return false;
    }

    public function deleteAtId(int $id): bool
    {
        $sql = "DELETE FROM $this->tableName WHERE id = :id;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function validateArgsUpdate(array $args): bool
    {
        return array_key_exists('text', $args) && array_key_exists('id', $args);
    }
    private function validateArgsCreate(array $args): bool
    {
        return array_key_exists('text', $args);
    }
}