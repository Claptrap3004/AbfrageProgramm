<?php

namespace quiz;

use PDO;

class QuizContentDBHandler extends IdTextDBHandler
{
    public function __construct(KindOf $kindOf)
    {
        parent::__construct($kindOf);
        $this->setTablename();
//       $this->createTables();
    }

    private function setTablename(): void
    {
        $factory = Factory::getFactory();
        $user = $factory->createUser($_SESSION['UserId']);
        $email = $user->getEmail();
        $email = str_replace('.', '_', $email);
        $email = str_replace('@', '_', $email);
        $this->tableName = $this->tableName . $email;
    }

    // creates new tables for tracking quizContent first and then populates quiz_content table
    // with list of question-ids setting first to actual
    public function create(array $args): int
    {
        if (!$this->validateArgsCreate($args)) return -1;
        $this->createTables();
        $sql = "INSERT INTO $this->tableName (question_id,is_actual) VALUES (:question_id,:is_actual);";
        $stmt = $this->connection->prepare($sql);
        foreach ($args['question_ids'] as $index => $questionId){
                $isActual = $index == 0 ? 1 : 0;
                $stmt->execute([':question_id' => $questionId, ':is_actual' => $isActual]);}
        return 1;
    }

    private function createTables(): void
    {
        $track = $this->getTrackTableName();
        $sqls = [];
        $sqls[] = "DROP TABLE IF EXISTS $this->tableName;";
        $sqls[] = "CREATE TABLE $this->tableName (id INT PRIMARY KEY AUTO_INCREMENT,
        question_id INT, 
        is_actual BOOL);";
        $sqls[] = "DROP TABLE IF EXISTS $track;";
        $sqls[] = "CREATE TABLE $track(id INT PRIMARY KEY AUTO_INCREMENT,
        question_id INT,
         answer_id INT);";
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
        $data = $this->findAll();
        $questions = [];
        foreach ($data as $item) $questions[] = $item['question_id'];
        if (!in_array($id, $questions)) return [];
        $table = $this->getTrackTableName();
        $sql = "SELECT * FROM $table WHERE question_id = :id;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchALl(PDO::FETCH_ASSOC);
    }

    // returns question ids (content of quiz)
    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->tableName;";
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
            $stmt->execute([':id' => $args['question_id']]);
            foreach ($args['answers'] as $answerId) {
                $sql = "INSERT INTO $table (question_id,answer_id) VALUES (:question_id,:answer_id)";
                $stmt = $this->connection->prepare($sql);
                $success = $stmt->execute([':question_id' => $args['question_id'], ':answer_id' => $answerId]);
                if (!$success) return false;
            }
            $this->setNextAsActual($args['question_id']);
            return true;
        }
        return false;
    }


    private function setNextAsActual(int $questionId): void
    {
        $sql = "SELECT id FROM $this->tableName WHERE question_id = :question_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['question_id'=>$questionId]);
        $id = $stmt->fetch(2)['id'];
        $sql = "UPDATE $this->tableName SET is_actual = :is_actual WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id'=>$id, 'is_actual'=>0]);
        $stmt->execute([':id'=>$id + 1, 'is_actual'=>1]);
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

    public function setActualFirst():void
    {
        $sql = "UPDATE $this->tableName SET is_actual = 1 WHERE id = 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $sql = "UPDATE $this->tableName SET is_actual = 0 WHERE id NOT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
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