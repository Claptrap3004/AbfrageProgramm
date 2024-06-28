<?php

namespace quiz;


use PDO;

class QuestionDBHandler extends IdTextDBHandler
{

    public function __construct(KindOf $kindOf)
    {
        parent::__construct($kindOf);
    }


    /**
     * without param simply calls parent find all method to provide all question data sets including following keys:
     * 'id', 'category_id', 'user_id' and 'text'
     * filter array may contain 'categoryIds' and / or 'userIds' key which hold array of appropriate ids
     * @param array $filters
     * @return array listed keys
     */
    public function findAll(array $filters = []): array
    {
        if ($filters !== []) return $this->findFiltered($filters);
        return parent::findAll();
    }

    /**
     * returns question data sets matching filter containing keys listed iin findAll DOC
     * @param array $filters
     * @return array
     */
    private function findFiltered(array $filters): array
    {
        if (array_key_exists('categoryIds', $filters) && array_key_exists('userIds', $filters)){
            $sql = "SELECT * FROM $this->tableName WHERE category_id IN (:categoryIds) AND user_id IN (:userIds);";
            $categoryIds = implode(',', $filters['categoryIds']);
            $userIds = implode(',', $filters['userIds']);
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([':categoryIds' => $categoryIds, ':userIds' => $userIds]);
        }
        elseif (array_key_exists('categoryIds', $filters)){
            $sql = "SELECT * FROM $this->tableName WHERE category_id IN (:categoryIds);";
            $categoryIds = implode(',', $filters['categoryIds']);
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([':categoryIds' => $categoryIds]);
        }
        elseif (array_key_exists('userIds', $filters)){
            $sql = "SELECT * FROM $this->tableName WHERE user_id IN (:userIds);";
            $userIds = implode(',', $filters['userIds']);
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([':userIds' => $userIds]);
        }
        else return [];
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    /**
     * checks against existence of necessary keys in args.
     * required keys are 'text','category_id' and 'user_id'
     * @param array $args
     * @return bool
     */
    protected function validateArgsCreate(array $args): bool
    {
        return array_key_exists('text', $args) &&
            array_key_exists('category_id', $args) &&
            array_key_exists('user_id', $args);
    }

}