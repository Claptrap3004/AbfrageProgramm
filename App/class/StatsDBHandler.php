<?php

namespace quiz;


use PDO;

class StatsDBHandler extends IdTextDBHandler
{
    private int $userId;

    public function __construct(KindOf $kindOf, int $userId)
    {
        parent::__construct($kindOf);
        $this->userId = $userId;
    }

    public function create(array $args): int
    {
        if ($this->validateArgsCreate($args)){
            $sql = "INSERT INTO $this->tableName (user_id, question_id, times_asked, times_right) 
                    VALUES (:user_id, :question_id, :times_asked, :times_right);";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':user_id', $this->userId);
            $stmt->bindParam(':question_id', $args['question_id']);
            $stmt->bindParam(':times_asked', $args['times_asked']);
            $stmt->bindParam(':times_right', $args['times_right']);
            $stmt->execute();
            return $this->connection->lastInsertId();
        }
        return -1;
    }

    // since there is no need to create single relation objects this implementation of findById provides an array of
    // answerIds and the value of isRight of the answerId that refer to a question id instead of providing information
    // of a single relation by its id
    public function findById(int $id): array
    {
        $sql = "SELECT * FROM $this->tableName WHERE question_id = :question_id AND user_id = :user_id;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':question_id', $id);
        $stmt->bindParam(':user_id', $this->userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update(array $args): bool
    {
        if ($this->validateArgsUpdate($args)) {
            $sql = "UPDATE $this->tableName SET times_asked = :times_asked, times_right = :times_right WHERE id = :id;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $args['id']);
            $stmt->bindParam(':times_asked', $args['times_asked']);
            $stmt->bindParam(':times_right', $args['times_right']);
            return $stmt->execute();
        }
        return false;
    }
    protected function validateArgsCreate(array $args): bool
    {
        return array_key_exists('question_id', $args) &&
            array_key_exists('user_id', $args) &&
            array_key_exists('times_asked', $args) &&
            array_key_exists('times_right', $args);
    }

}