<?php

namespace quiz;

class EditController extends Controller
{
    public function index()
    {

    }

    public function import(): void
    {
        $importer = new CSVImporterNiklas();
        $importer->readCSV('../Files/quiz1.csv');
        $this->deleteInvalidQuestions();
        $this->deleteDuplicates();
    }

    public function export(): void
    {
        $exporter = new CSVImporterStandard();
        $all = KindOf::QUESTION->getDBHandler()->findAll();
        $questionIds = [];
        foreach ($all as $item) $questionIds[] = $item['id'];
        var_dump($questionIds);
        $exporter->writeCSV('export.csv',$questionIds );
    }

    public function editQuestion(int $questionId = null)
    {
        if ($questionId !== null){
            if ($_SERVER['REQUEST_METHOD'] == "POST") {

            }
            else {
                try {
                    $jsData = json_encode(Factory::getFactory()->createEditQuestionById($questionId));
                    $jsDataCategories = json_encode(Factory::getFactory()->findAllIdTextObject(KindOf::CATEGORY));
                    $this->view('edit/editQuestion', ['jsData' => $jsData, 'jsDataCategories' => $jsDataCategories]);
                } catch (\Exception $e) {
                }
            }
        }
    }

    public function createQuestion()
    {

    }

    public function deleteDuplicates(): void
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
        foreach ($questiondata as $data) try {
            $questions[] = Factory::getFactory()->createQuizQuestionById($data['id']);
        } catch (\Exception $e) {
            continue;
        }
        $invalidQuestions = [];
        foreach ($questions as $question){ if ($question->getRightAnswers() == []) $invalidQuestions[] = $question->getId();
        }
        foreach ($invalidQuestions as $invalidQuestion) KindOf::QUESTION->getDBHandler()->deleteAtId($invalidQuestion);

    }

}