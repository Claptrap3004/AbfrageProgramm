<?php
// responsible for creating objects of classes IdText, QuizQuestion, EditQuestion, Stats and so on
// DBHandler id provided through KindOf enum
namespace quiz;

class Factory
{
    private CanConnectDB $connector;
    private CanHandleDB $dbHandler;

    public function __construct()
    {
        $this->connector = new MariaDBConnector();
    }

    public function createIdTextObject(string $text, KindOf $kindOf): ?IdText
    {
        $this->dbHandler = $kindOf->getDBHandler($this->connector);
        $id = $this->dbHandler->create(['text' => $text]);
        return $id > 0 ? new IdText($id,$text,$kindOf): null;
    }
    public function findIdTextObjectById(int $id, KindOf $kindOf): ?IdText
    {
        $this->dbHandler = $kindOf->getDBHandler($this->connector);
        $infos = $this->dbHandler->findById($id);
        return $id > 0 ? new IdText($id,$infos['text'],$kindOf): null;
    }

    public function findAllIdTextObject(KindOf $kindOf): array
    {
        $answers = [];
        $this->dbHandler = $kindOf->getDBHandler($this->connector);
        $answerInfos = $this->dbHandler->findAll();
        foreach ($answerInfos as $answerInfo)
            $answers[] = new IdText($answerInfo['id'],
                                    $answerInfo['text'], $kindOf);
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
            if ($relation['isRight']) $rightAnswers[] = $answer;
            else $wrongAnswers[] = $answer;
        }
        // fake stats for now
        $stats = new Stats(1,0,0);
        return new QuizQuestion($id,
                                $questionAttributes['text'],
                                $category,
                                $rightAnswers,
                                $wrongAnswers,
                                $stats);
    }

    public function crateStatsByQuestionId(int $questionId): Stats
    {
        $this->dbHandler = KindOf::STATS->getDBHandler($this->connector);
        $statsAttributes = $this->dbHandler->findById($questionId);
        return new Stats($statsAttributes['id'],
                        $statsAttributes['user_id'],
                        $statsAttributes['question_id'],
                        $statsAttributes['times_asked'],
                        $statsAttributes['times_right']);
    }







}