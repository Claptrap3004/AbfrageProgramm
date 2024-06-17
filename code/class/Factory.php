<?php

namespace quiz;

class Factory
{
    private CanConnectDB $connector;
    private CanHandleDB $dbHandler;

    public function __construct()
    {
        $this->connector = new MariaDBConnector();
    }

    public function createIdTextObject(string $text, KindOfIdText $kindOfIdText): ?IdText
    {
        $this->dbHandler = $kindOfIdText->getDBHandler($this->connector);
        $id = $this->dbHandler->create(['text' => $text]);
        return $id > 0 ? new IdText($id,$text,$kindOfIdText): null;
    }
    public function findIdTextObjectById(int $id, KindOfIdText $kindOfIdText): ?IdText
    {
        $this->dbHandler = $kindOfIdText->getDBHandler($this->connector);
        $infos = $this->dbHandler->findById($id);
        return $id > 0 ? new IdText($id,$infos['text'],$kindOfIdText): null;
    }

    public function findAllIdTextObject(KindOfIdText $kindOfIdText): array
    {
        $answers = [];
        $this->dbHandler = $kindOfIdText->getDBHandler($this->connector);
        $answerInfos = $this->dbHandler->findAll();
        foreach ($answerInfos as $answerInfo)
            $answers[] = new IdText($answerInfo['id'], $answerInfo['text'], $kindOfIdText);
        return $answers;
    }





}