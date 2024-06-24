<?php

namespace quiz;

use PDO;

class UserDBHandler extends IdTextDBHandler
{

    public function create(array $args): int
    {
        if ($this->validateArgsCreate($args)) {
            $sql = "INSERT INTO $this->tableName (username, email, password) VALUES (:username, :email, :password);";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':username', $args['username']);
            $stmt->bindParam(':email', $args['email']);
            $stmt->bindParam(':password', $args['password']);
            $stmt->execute();
            return $this->connection->lastInsertId();
        }
        return -1;
    }

    public function update(array $args): bool
    {
        if ($this->validateArgsUpdate($args)) {
            $sql = "UPDATE $this->tableName SET username = :username,
                 email = :email,
                 password = :password
                 WHERE id = :id;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $args['id']);
            $stmt->bindParam(':username', $args['username']);
            $stmt->bindParam(':email', $args['email']);
            $stmt->bindParam(':password', $args['password']);
            return $stmt->execute();
        }
        return false;
    }

    protected function validateArgsCreate(array $args): bool
    {
        return array_key_exists('username', $args) &&
            array_key_exists('email', $args) &&
            array_key_exists('password', $args);
    }
}