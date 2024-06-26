<?php
// Dealing as a kind of Controller for all classes that need to provide CRUD functionality
// Holding information of table name and providing correct handler for CRUD in db

// for dev the user_id is set to 2 hardcoded. After User class and functionality is implemented this must be changed to
// something like $_Session['userId']

namespace quiz;



enum KindOf : string
{
    case CATEGORY = 'category';
    case ANSWER = 'answer';

    case QUESTION = 'question';
    case STATS = 'stats';
    case USER = 'user';
    case RELATION = 'answerToQuestion';
    case QUIZCONTENT ='quiz_content_';

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

    public function getDBHandler(): CanHandleDB
    {
        $handler = new IdTextDBHandler($this);
        return match ($this->getName()) {
            'CATEGORY', 'ANSWER' => new IdTextDBHandler($this),
            'QUESTION' => new QuestionDBHandler($this),
            'RELATION' => new RelationDBHandler($this),
            'STATS' => new StatsDBHandler($this,2),
            'USER' => new UserDBHandler($this),
            'QUIZCONTENT' => new QuizContentDBHandler($this),
            default => $handler
        };
    }
}
