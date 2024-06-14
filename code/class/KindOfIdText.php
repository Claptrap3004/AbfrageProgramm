<?php
namespace quiz;

enum KindOfIdText : string
{
    case CATEGORY = 'category';
    case ANSWER = 'answer';

    case QUESTION = 'question';
    case STATS = 'stats';
    case USER = 'user';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->value;
    }
}
