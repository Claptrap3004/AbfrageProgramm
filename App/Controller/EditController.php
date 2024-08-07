<?php

namespace quiz;

use Exception;

class EditController extends Controller
{
    public function index()
    {

    }

    public function selectImport(): void
    {

    }

    public function import(): void
    {
        $importer = new CSVImporterStandard();
        $importer->readCSV('../Files/jscerti.csv');
        $this->cleanUp();
    }

    public function importNiklas(): void
    {
        $importer = new CSVImporterNiklas();
        $importer->readCSV('../Files/quiz1.csv');
        $this->cleanUp();
    }


    public function export(): void
    {
        $exporter = new CSVImporterStandard();
        $all = KindOf::QUESTION->getDBHandler()->findAll();
        $questionIds = [];
        foreach ($all as $item) $questionIds[] = $item['id'];
        $exporter->writeCSV('export.csv', $questionIds);
    }

    private function cleanUp(): void
    {
        $this->deleteInvalidQuestions();
        $this->deleteDuplicates();
    }

    public function editQuestion(int|string|null $questionId = null): void
    {
        if ($questionId !== null && (int)($questionId) >= 0) {

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $answerArray = $_POST['answerArrayJSON'] ?? '';
                $id = $_POST['editQuestionId'] ?? null;
                $text = $_POST['editQuestionText'] ?? '';
                $explanation = $_POST['editQuestionExplanation'] ?? '';
                $categoryId = $_POST['editCategoryId'] ?? null;
                $categoryText = $_POST['editCategoryText'] ?? null;
                $answers = json_decode($answerArray);

                // if new question is created no valid id will be submitted, hence new question needs to be created
                if (!$id) $id = DBFactory::getFactory()->createNewQuizQuestion($text, $categoryId);

                // if user selects create new category id will be 0, hence new category needs to be created
                if ($categoryId < 1) $categoryId = DBFactory::getFactory()->createCategory($categoryText);

                // new EditQuestion instance needs to be created and updated with submitted user inputs, after
                // EditQuestion contains all edited values it needs to update itself to db
                $this->updateEditedQuestion($id, $categoryId, $text, $explanation, $answers);
                $this->view(UseCase::WELCOME->getView(), []);
            }
            else $this->showEditQuestion($questionId);
        } else {
            // if method has been called with no proper parameter it will return user to main screen
            $this->view(UseCase::WELCOME->getView(), []);
        }
    }

    // sends data of question to according view in json format
    private function showEditQuestion(int $questionId): void
    {
        try {
            $jsData = json_encode(Factory::getFactory()->createEditQuestionById($questionId));
            $jsDataCategories = json_encode(Factory::getFactory()->findAllIdTextObject(KindOf::CATEGORY));
            $this->view(UseCase::EDIT_QUESTION->getView(), ['jsData' => $jsData, 'jsDataCategories' => $jsDataCategories]);
        } catch (Exception $e){ $this->view(UseCase::WELCOME->getView(), []);}

    }

    private function updateEditedQuestion(int $id, int $categoryId, string $text, string $explanation, array $answers): void
    {
        try {
            $editQuestion = $this->factory->createEditQuestionById($id);
            $editQuestion->setText($text);
            $editQuestion->setCategory($this->factory->findIdTextObjectById($categoryId, KindOf::CATEGORY));
            $editQuestion->setExplanation($explanation);
            $editQuestion->resetAnswers();
            if ($answers) {
                foreach ($answers as $answer) {
                    $answerId = DBFactory::getFactory()->createAnswer($answer->text);
                    $answerToRelate = $this->factory->findIdTextObjectById($answerId, KindOf::ANSWER);
                    $editQuestion->setAnswer($answerToRelate, $answer->isRight);
                }
            }
            $editQuestion->saveQuestion();
        } // if anything goes wrong question just stays unchanged
        catch (Exception $e) {
        }

    }

    /**
     * if question text is not unique in db the first created question is being kept and all duplicates are removed
     * @return void
     */
    private function deleteDuplicates(): void
    {
        $questiondata = KindOf::QUESTION->getDBHandler()->findAll();
        $texts = [];
        $duplicateIds = [];
        foreach ($questiondata as $data) {
            $key = trim($data['text']);
            if (array_key_exists($key, $texts)) $duplicateIds[] = $data['id'];
            else $texts[$key] = $data['id'];
        }
        foreach ($duplicateIds as $id) KindOf::QUESTION->getDBHandler()->deleteAtId($id);
    }

    /**
     * if question has no answers set to be correct the question is being removed
     * @return void
     */
    private function deleteInvalidQuestions(): void
    {
        $questiondata = KindOf::QUESTION->getDBHandler()->findAll();
        $questions = [];
        foreach ($questiondata as $data) try {
            $questions[] = Factory::getFactory()->createQuizQuestionById($data['id']);
        } catch (\Exception $e) {
            continue;
        }
        $invalidQuestions = [];
        foreach ($questions as $question) {
            if ($question->getRightAnswers() == []) $invalidQuestions[] = $question->getId();
        }
        foreach ($invalidQuestions as $invalidQuestion) KindOf::QUESTION->getDBHandler()->deleteAtId($invalidQuestion);

    }

}