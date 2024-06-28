<?php

namespace quiz;

class DBHandlerProvider
{

    private static ?IdTextDBHandler $answerDBHandler = null;
    private static ?IdTextDBHandler $categoryDBHandler = null;
    private static ?QuestionDBHandler $questionDBHandler = null;
    private static ?RelationDBHandler $relationDBHandler = null;
    private static ?StatsDBHandler $statsDBHandler = null;
    private static ?UserDBHandler $userDBHandler = null;
    private static ?QuizContentDBHandler $quizContentDBHandler = null;

    /**
     * provides appropriate DBHandler depending on KindOf being ANSWER or CATEGORY, if any other KindOF element this
     * will return null
     * @param KindOf $kindOf
     * @return IdTextDBHandler|null
     */
    public static function getIdTextDBHandler(KindOf $kindOf): ?IdTextDBHandler
    {
        switch ($kindOf) {
            case KindOf::ANSWER :
                if (!self::$answerDBHandler) self::$answerDBHandler = new IdTextDBHandler($kindOf);
                return self::$answerDBHandler;
            case KindOf::CATEGORY :
                if (!self::$categoryDBHandler) self::$categoryDBHandler = new IdTextDBHandler($kindOf);
                return self::$categoryDBHandler;
            default:
                return null;
        }
    }


    /**
     * sets param idTextDBHandler to answer or category db handler depending on KindOf being ANSWER or CATEGORY,
     * if any other kindOf element is passed nothing changes
     * @param IdTextDBHandler $idTextDBHandler
     * @param KindOf $kindOf
     * @return void
     */
    public static function setIdTextDBHandler(IdTextDBHandler $idTextDBHandler, KindOf $kindOf): void
    {
        switch ($kindOf) {
            case KindOf::ANSWER :
                self::$answerDBHandler = $idTextDBHandler;
                break;
            case KindOf::CATEGORY :
                self::$categoryDBHandler = $idTextDBHandler;
                break;
            default:
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
        if (!self::$relationDBHandler) self::$relationDBHandler = new RelationDBHandler(KindOf::RELATION);
        return self::$relationDBHandler;
    }

    public static function setRelationDBHandler(RelationDBHandler $relationDBHandler): void
    {
        self::$relationDBHandler = $relationDBHandler;
    }

    public static function getStatsDBHandler(): StatsDBHandler
    {
        if (!self::$statsDBHandler) self::$statsDBHandler = new StatsDBHandler(KindOf::STATS, $_SESSION['UserId']);
        return self::$statsDBHandler;
    }

    public static function setStatsDBHandler(StatsDBHandler $statsDBHandler): void
    {
        self::$statsDBHandler = $statsDBHandler;
    }

    public static function getUserDBHandler(): UserDBHandler
    {
        if (!self::$userDBHandler) self::$userDBHandler = new UserDBHandler(KindOf::STATS);

        return self::$userDBHandler;
    }

    public static function setUserDBHandler(UserDBHandler $userDBHandler): void
    {
        self::$userDBHandler = $userDBHandler;
    }

    public static function getQuizContentDBHandler(): QuizContentDBHandler
    {
        if (!self::$quizContentDBHandler) self::$quizContentDBHandler = new QuizContentDBHandler(KindOf::QUIZCONTENT);

        return self::$quizContentDBHandler;
    }

    public static function setQuizContentDBHandler(QuizContentDBHandler $quizContentDBHandler): void
    {
        self::$quizContentDBHandler = $quizContentDBHandler;
    }


}