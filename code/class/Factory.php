<?php
// responsible for creating objects of classes IdText, QuizQuestion, EditQuestion, Stats and so on
// DBHandler is provided through KindOf enum
namespace quiz;

class Factory
{
    private CanConnectDB $connector;
    private CanHandleDB $dbHandler;

    public function __construct(CanConnectDB $connector = null)
    {
        $this->connector = $connector == null ? new MariaDBConnector(): $connector;
    }

    public function createIdTextObject(string $text, KindOf $kindOf): ?IdText
    {
        $this->dbHandler = $kindOf->getDBHandler($this->connector);
        $id = $this->dbHandler->create(['text' => $text]);
        return $id > 0 ? new IdText($id,
                                    $text,
                                    $kindOf,
                                    $this->connector)
                        : null;
    }
    public function findIdTextObjectById(int $id, KindOf $kindOf): ?IdText
    {
        $this->dbHandler = $kindOf->getDBHandler($this->connector);
        $infos = $this->dbHandler->findById($id);
        return $id > 0 ? new IdText($id,
                                    $infos['text'],
                                    $kindOf,
                                    $this->connector)
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







}