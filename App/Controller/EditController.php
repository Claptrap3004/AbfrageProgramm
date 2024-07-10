<?php

namespace quiz;

class EditController
{
    public function index()
    {

    }

    public function import(): void
    {
        $importer = new CSVImporter();
        $importer->readCSV('../Files/quiz1.csv');
    }

    public function export()
    {

    }

    public function editQuestion()
    {

    }

    public function createQuestion()
    {

    }

    public function deleteDuplicates()
    {
        $questiondata = KindOf::QUESTION->getDBHandler()->findAll();
        $texts = [];
        $duplicateIds = [];
        foreach ($questiondata as $data){
            $key = trim($data['text']);
            if (array_key_exists($key, $texts)) $duplicateIds[] = $data['id'];
            else $texts[$key] = $data['id'];
        }
        var_dump($duplicateIds);
        foreach ($duplicateIds as $id) KindOf::QUESTION->getDBHandler()->deleteAtId($id);
    }

    public function deleteInvalidQuestions():void
    {
        $questiondata = KindOf::QUESTION->getDBHandler()->findAll();
        $questions = [];
        foreach ($questiondata as $data) $questions[] = Factory::getFactory()->createQuizQuestionById($data['id']);
        $invalidQuestions = [];
        foreach ($questions as $question){ if ($question->getRightAnswers() == []) $invalidQuestions[] = $question->getId();
        }
        foreach ($invalidQuestions as $invalidQuestion) KindOf::QUESTION->getDBHandler()->deleteAtId($invalidQuestion);

    }

}