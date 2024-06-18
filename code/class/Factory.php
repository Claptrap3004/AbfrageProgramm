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
            $answers[] = new IdText($answerInfo['id'], $answerInfo['text'], $kindOf);
        return $answers;
    }

    public function createQuizQuestionById(int $id): ?QuizQuestion
    {
        $this->dbHandler = KindOf::QUESTION->getDBHandler($this->connector);
        $questionAttributes = $this->dbHandler->findById($id);
        return null;
    }





}