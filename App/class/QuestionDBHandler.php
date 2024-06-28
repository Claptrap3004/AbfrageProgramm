<?php

namespace quiz;



use PDO;

class QuestionDBHandler extends IdTextDBHandler
{

    public function __construct(KindOf $kindOf)
    {
        parent::__construct($kindOf);
    }

    public function create(array $args): int
    {
        if ($this->validateArgsCreate($args)){
            $sql = "INSERT INTO $this->tableName (category_id, user_id, text) VALUES (:category_id, :user_id, :text);";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':category_id', $args['category_id']);
            $stmt->bindParam(':user_id', $args['user_id']);
            $stmt->bindParam(':text', $args['text']);
            $stmt->execute();
            $id = $this->connection->lastInsertId();
            KindOf::STATS->getDBHandler()->create(['user_id' => 2,'question_id'=> $id,'times_asked' =>0, 'times_right'=>0]);
            $stmt->bindParam(':question_id', $args['question_id']);
            $stmt->bindParam(':times_asked', $args['times_asked']);
            $stmt->bindParam(':times_right', $args['times_right']);
            return $id;
        }
        return -1;
    }

    public function findAll(array $filters = []): array
    {
        if ($filters  !== []) return $this->findFiltered($filters);
        return parent::findAll();
    }
    /**
     * @param array $filters
     * @return array
     */
    private function findFiltered(array $filters): array
    {

        $sql = "SELECT * FROM $this->tableName WHERE category_id in (:categories);";
        $categories = implode(',', $filters['categoryIds']);
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':categories' => $categories]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function update(array $args): bool
    {
        if ($this->validateArgsUpdate($args)) {
            $sql = "UPDATE $this->tableName SET category_id = :category_id, user_id = :user_id, text = :text WHERE id = :id;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $args['id']);
            $stmt->bindParam(':category_id', $args['category_id']);
            $stmt->bindParam(':user_id', $args['user_id']);
            $stmt->bindParam(':text', $args['text']);
            return $stmt->execute();
        }
        return false;
    }
    protected function validateArgsCreate(array $args): bool
    {
        return array_key_exists('text', $args) &&
            array_key_exists('category_id', $args) &&
            array_key_exists('user_id', $args);
    }

}