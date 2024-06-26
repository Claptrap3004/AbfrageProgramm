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


    /**
     * $id in this case represents the question id the stats data is related to, provides stats data set for question
     * depending on actual user
     * data set contains following keys: 'id', 'user_id', 'question_id', 'times_asked' and 'times_right'
     * @param int $id
     * @return array
     */
    public function findById(int $id): array
    {
        $sql = "SELECT * FROM $this->tableName WHERE question_id = :question_id AND user_id = :user_id;";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':question_id'=> $id,':user_id'=> $this->userId]);
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