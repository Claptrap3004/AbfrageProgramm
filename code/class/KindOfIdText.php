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

    public function getDBHandler(CanConnectDB $connectDB): CanHandleDB
    {
        $handler = new IdTextDBHandler($this, $connectDB);
        switch ($this->getName()){
            case 'CATEGORY':
            case 'ANSWER' : return new IdTextDBHandler($this, $connectDB);

        }
        return $handler;
    }
}
