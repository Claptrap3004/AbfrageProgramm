<?php
// Dealing as a kind of Controller for all classes that need to provide CRUD functionality
// Holding information of table name and providing correct handler for CRUD in db

namespace quiz;

enum KindOf : string
{
    case CATEGORY = 'category';
    case ANSWER = 'answer';

    case QUESTION = 'question';
    case STATS = 'stats';
    case USER = 'user';
    case RELATION = 'answerToQuestion';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    // value of enum is holding table name
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->value;
    }

    // handler provider

    public function getDBHandler(CanConnectDB $connectDB): CanHandleDB
    {
        $handler = new IdTextDBHandler($this, $connectDB);
        return match ($this->getName()) {
            'CATEGORY', 'ANSWER' => new IdTextDBHandler($this, $connectDB),
            'QUESTION' => new QuestionDBHandler($this,$connectDB),
            'RELATION' => new RelationDBHandler($this,$connectDB),
            'STATS' => new StatsDBHandler($this,$connectDB,2),
            default => $handler,
        };
    }
}
