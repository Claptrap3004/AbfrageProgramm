<?php
// responsible for creating objects of classes IdText, QuizQuestion, EditQuestion, Stats and so on
// DBHandler is provided through KindOf enum

use quiz\CanConnectDB;
use quiz\CanHandleDB;
use quiz\DataBase;
use quiz\MariaDBConnector;

class Factory extends DataBase
{
    private CanHandleDB $dbHandler;



    public function createIdTextObject(string $text, KindOf $kindOf): ?IdText
    {
        $this->dbHandler = $kindOf->getDBHandler();
        $id = $this->dbHandler->create(['text' => $text]);
        return $id > 0 ? new IdText($id,
                                    $text,
                                    $kindOf)
                        : null;
    }
    public function findIdTextObjectById(int $id, KindOf $kindOf): ?IdText
    {
        $this->dbHandler = $kindOf->getDBHandler();
        $infos = $this->dbHandler->findById($id);
        return $id > 0 ? new IdText($id,
                                    $infos['text'],
                                    $kindOf
                                    )
                        : null;
    }

    public function findAllIdTextObject(KindOf $kindOf): array
    {
        $answers = [];
        $this->dbHandler = $kindOf->getDBHandler($this->connector);
        $answerInfos = $this->dbHandler->findAll();
        foreach ($answerInfos as $answerInfo)
            $answers[] = new IdText($answerInfo['id'],
                                    $answerInfo['text'],
                                    $kindOf,
                                    $this->connector);
        return $answers;
    }

    public function createQuizQuestionById(int $id): ?QuizQuestion
    {
        $this->dbHandler = KindOf::QUESTION->getDBHandler($this->connector);
        $questionAttributes = $this->dbHandler->findById($id);
        $category = $this->findIdTextObjectById($questionAttributes['category_id'],
                                        KindOf::CATEGORY);

        $this->dbHandler = KindOf::RELATION->getDBHandler($this->connector);
        $relations = $this->dbHandler->findById($id);
        $rightAnswers = [];
        $wrongAnswers = [];
        foreach ($relations as $relation){
            $answer = $this->findIdTextObjectById($relation['answer_id'],
                                        KindOf::ANSWER);
            if ($relation['is_right']) $rightAnswers[] = $answer;
            else $wrongAnswers[] = $answer;
        }

        $stats = $this->createStatsByQuestionId($id);
        return new QuizQuestion($id,
                                $questionAttributes['text'],
                                $this->connector,
                                $category,
                                $rightAnswers,
                                $wrongAnswers,
                                $stats);
    }

    public function createStatsByQuestionId(int $questionId): Stats
    {
        $this->dbHandler = KindOf::STATS->getDBHandler($this->connector);
        $statsAttributes = $this->dbHandler->findById($questionId);
        return new Stats($statsAttributes['id'],
                        $statsAttributes['user_id'],
                        $statsAttributes['question_id'],
                        $this->connector,
                        $statsAttributes['times_asked'],
                        $statsAttributes['times_right']);
    }

    public function createEditQuestionById(int $id):EditQuestion
    {
        $this->dbHandler = KindOf::QUESTION->getDBHandler($this->connector);
        $questionAttributes = $this->dbHandler->findById($id);
        $category = $this->findIdTextObjectById($questionAttributes['category_id'],
            KindOf::CATEGORY);
        $this->dbHandler = KindOf::RELATION->getDBHandler($this->connector);
        $relations = $this->dbHandler->findById($id);

        $rightAnswers = [];
        $wrongAnswers = [];
        foreach ($relations as $relation){
            $answer = $this->findIdTextObjectById($relation['answer_id'],
                KindOf::ANSWER);
            if ($relation['is_right']) $rightAnswers[] = $answer;
            else $wrongAnswers[] = $answer;
        }
        return new EditQuestion($id,
            $questionAttributes['text'],
            $this->connector,
            $category,
            $rightAnswers,
            $wrongAnswers);
    }







}