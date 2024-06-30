<?php

namespace quiz;

enum Filters
{
    case CATEGORY;
    case USER;

    public function createArray(array $ids): array
    {
        return match ($this->name){
            'CATEGORY' => ['categoryIds'=>$ids],
            'USER' => ['userIds' => $ids],
            default => $ids
        };
    }

    public function createWhereClauseAndBindings(array $ids): array
    {
        $binding = [];
        $sql = " WHERE " . match ($this->name){
                'CATEGORY' => 'category_id',
                'USER' => 'user_id',
                default => ''
            };
        $sql  .= ' IN (';
        foreach ($ids as $index=>$value) {
            $binding[":id$index"] = $value;
            $sql .= ":id$index, ";
        }
        $sql = rtrim($sql,', ');
        $sql .= ');';
        return ['sql'=>$sql,'binding' => $binding];
    }

}
