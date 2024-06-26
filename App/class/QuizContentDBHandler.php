<?php

namespace quiz;

use PDO;

class QuizContentDBHandler extends IdTextDBHandler
{
    public function __construct(KindOf $kindOf)
    {
        parent::__construct($kindOf);
        $this->setTabename();
    }

    private function setTabename(): void
    {
        $factory = Factory::getFactory();
        $user = $factory->createUser($_SESSION['UserId']);
        $email = $user->getEmail();
        str_replace('.', '_', $email);
        str_replace('@', '_', $email);
        $this->tableName = $this->tableName . $email;
    }

    // creates new tables for tracking quizContent first and then populates quiz_content table
    // with list of question-ids setting first to actual
    public function create(array $args): int
    {
        $this->createTables();
        foreach ($args as $index => $questionId)
            if ($this->validateArgsCreate($args)) {
                $sql = "INSERT INTO $this->tableName (question_id,is_actual) VALUES (:question_id,:is_actual);";
                $stmt = $this->connection->prepare($sql);
                $isActual = $index == 0 ? 1 : 0;
                $stmt->execute([':question_id' => $questionId, ':is_actual' => $isActual]);
            }
        return 1;
    }

    private function createTables(): void
    {
        $track = $this->getTrackTableName();
        $sqls = [];
        $sqls[] = "DROP TABLE IF EXITS $this->tableName;";
        $sqls[] = "CREATE TABLE $this->tableName (id INT PRIMARY KEY AUTO_INCREMENT,
        question_id INT, 
        is_actual BOOL);";
        $sqls[] = "DROP TABLE IF EXITS $track;";
        $sqls[] = "CREATE TABLE $track(id INT PRIMARY KEY AUTO_INCREMENT,
        question_id INT,
         answer_id INT,
         FOREIGN KEY (question_id) REFERENCES $this->tableName(question_id));";
        foreach ($sqls as $sql) {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
        }
    }

    private function getTrackTableName(): string
    {
        return 'track_' . $this->tableName;
    }

    // returns given answers to question if in quiz content
    public function findById(int $id): array
    {
        $questions = $this->findAll();
        if (!in_array($id, $questions)) return [];
        $table = $this->getTrackTableName();
        $sql = "SELECT * FROM $table WHERE question_id = :id;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // returns question ids (content of quiz)
    public function findAll(): array
    {
        $sql = "SELECT question_id FROM $this->tableName;";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // expects array ['questionId' = int, 'answers' = [int]]
    public function update(array $args): bool
    {
        if ($this->validateArgsUpdate($args)) {
            $table = $this->getTrackTableName();
            $sql = "DELETE FROM $table WHERE question_id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([':id' => $args['questionId']]);
            foreach ($args['answers'] as $answerId) {
                $sql = "INSERT INTO $table (question_id,answer_id) VALUES (:question_id,:answer_id)";
                $stmt = $this->connection->prepare($sql);
                $success = $stmt->execute([':question_id' => $args['questionId'], ':answer_id' => $answerId]);
                if (!$success) return false;
            }
            return true;
        }
        return false;
    }

    // deletes single question from quiz content (also eventually given answers to that question)
    public function deleteAtId(int $id): bool
    {
        $track = $this->getTrackTableName();
        $sqls = [];
        $sqls[] = "DELETE FROM $track WHERE question_id = :id;";
        $sqls[] = "DELETE FROM $this->tableName WHERE question_id = :id;";
        foreach ($sqls as $sql) {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([':id' => $id]);
        }
        return true;
    }

    protected function validateArgsCreate(array $args): bool
    {
        return array_key_exists('question_ids', $args);
    }

    protected function validateArgsUpdate(array $args): bool
    {
        return array_key_exists('question_id', $args) && array_key_exists('answers', $args);
    }
}