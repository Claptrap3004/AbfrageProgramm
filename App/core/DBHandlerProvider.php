<?php

namespace quiz;

class DBHandlerProvider
{

    private static IdTextDBHandler $answerDBHandler;
    private static IdTextDBHandler $categoryDBHandler;
    private static QuestionDBHandler $questionDBHandler;
    private static RelationDBHandler $relationDBHandler;
    private static StatsDBHandler $statsDBHandler;
    private static UserDBHandler $userDBHandler;
    private static QuizContentDBHandler $quizContentDBHandler;

    public static function getIdTextDBHandler(KindOf $kindOf): IdTextDBHandler
    {
        switch($kindOf){
            case KindOf::ANSWER :
                if (!self::$answerDBHandler) self::$answerDBHandler = new IdTextDBHandler($kindOf);
                return self::$answerDBHandler;
            case KindOf::CATEGORY :
                if (!self::$categoryDBHandler) self::$categoryDBHandler = new IdTextDBHandler($kindOf);
                return self::$categoryDBHandler;
            default: return new IdTextDBHandler($kindOf);
        }
    }

    public static function setIdTextDBHandler(IdTextDBHandler $idTextDBHandler, KindOf $kindOf): void
    {
        switch($kindOf){
            case KindOf::ANSWER :
                self::$answerDBHandler = $idTextDBHandler;
                break;
            case KindOf::CATEGORY :
                self::$categoryDBHandler = $idTextDBHandler;
                break;
            default:
                throw new \Exception('Unexpected value');
        }
    }

    public static function getQuestionDBHandler(): QuestionDBHandler
    {
        if (!self::$questionDBHandler) self::$questionDBHandler = new QuestionDBHandler(KindOf::QUESTION);
        return self::$questionDBHandler;
    }

    public static function setQuestionDBHandler(QuestionDBHandler $questionDBHandler): void
    {
        self::$questionDBHandler = $questionDBHandler;
    }

    public static function getRelationDBHandler(): RelationDBHandler
    {
        if(!self::$relationDBHandler) self::$relationDBHandler = new RelationDBHandler(KindOf::RELATION);
        return self::$relationDBHandler;
    }

    public static function setRelationDBHandler(RelationDBHandler $relationDBHandler): void
    {
        self::$relationDBHandler = $relationDBHandler;
    }

    public static function getStatsDBHandler(): StatsDBHandler
    {
        if(!self::$statsDBHandler) self::$statsDBHandler = new StatsDBHandler(KindOf::STATS,$_SESSION['UserId']);
        return self::$statsDBHandler;
    }

    public static function setStatsDBHandler(StatsDBHandler $statsDBHandler): void
    {
        self::$statsDBHandler = $statsDBHandler;
    }

    public static function getUserDBHandler(): UserDBHandler
    {
        if(!self::$userDBHandler) self::$userDBHandler = new UserDBHandler(KindOf::STATS);

        return self::$userDBHandler;
    }

    public static function setUserDBHandler(UserDBHandler $userDBHandler): void
    {
        self::$userDBHandler = $userDBHandler;
    }

    public static function getQuizContentDBHandler(): QuizContentDBHandler
    {
        if(!self::$quizContentDBHandler) self::$quizContentDBHandler = new QuizContentDBHandler(KindOf::QUIZCONTENT);

        return self::$quizContentDBHandler;
    }

    public static function setQuizContentDBHandler(QuizContentDBHandler $quizContentDBHandler): void
    {
        self::$quizContentDBHandler = $quizContentDBHandler;
    }


}