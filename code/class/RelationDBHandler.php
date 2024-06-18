<?php

namespace quiz;

use PDO;

class RelationDBHandler extends IdTextDBHandler
{
    public function __construct(KindOf $kindOf, CanConnectDB $connectDB)
    {
        parent::__construct($kindOf, $connectDB);
    }

    public function create(array $args): int
    {
        if ($this->validateArgsCreate($args)){
            $sql = "INSERT INTO $this->tableName (question_id, answer_id, isRight) VALUES (:question_id, :answer_id, :isRight);";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':question_id', $args['question_id']);
            $stmt->bindParam(':answer_id', $args['answer_id']);
            $stmt->bindParam(':isRight', $args['isRight']);
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
        $sql = "SELECT answer_id, isRight FROM $this->tableName WHERE question_id = :id;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function update(array $args): bool
    {
        if ($this->validateArgsUpdate($args)) {
            $sql = "UPDATE $this->tableName SET question_id = :question_id, answer_id = :answer_id, isRight = :isRight WHERE id = :id;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $args['id']);
            $stmt->bindParam(':question_id', $args['question_id']);
            $stmt->bindParam(':answer_id', $args['answer_id']);
            $stmt->bindParam(':isRight', $args['isRight']);
            return $stmt->execute();
        }
        return false;
    }
    protected function validateArgsCreate(array $args): bool
    {
        return array_key_exists('question_id', $args) &&
            array_key_exists('answer_id', $args) &&
            array_key_exists('isRight', $args);
    }

}