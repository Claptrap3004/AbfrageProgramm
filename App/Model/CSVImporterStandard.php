<?php

namespace quiz;

class CSVImporterStandard implements CanHandleCSV
{
    private CanHandleDB $questionDBHandler;
    private CanHandleDB $answerDBHandler;
    private CanHandleDB $categoryDBHandler;
    private CanHandleDB $relationDBHandler;
    private Factory $factory;

    public function __construct()
    {
        $this->answerDBHandler = KindOf::ANSWER->getDBHandler();
        $this->categoryDBHandler = KindOf::CATEGORY->getDBHandler();
        $this->questionDBHandler = KindOf::QUESTION->getDBHandler();
        $this->relationDBHandler = KindOf::RELATION->getDBHandler();
        $this->factory = new Factory();
    }


    function readCSV(string $fileName)
    {
        $category = null;
        $categoryId = 0;
        $row = 0;
        if (($handle = fopen($fileName, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                if ($row === 1) continue;

                if ($data[0] !== $category)  {
                    $categoryId = 0;
                    $category = $data[0];
                    $existingCategories = $this->factory->findAllIdTextObject(KindOf::CATEGORY);
                    foreach ($existingCategories as $existingCategory){
                        if ($existingCategory->getText() == $category) $categoryId = $existingCategory->getId();
                    }
                    $categoryId = $categoryId == 0 ? $this->categoryDBHandler->create(['text' => $category]) : $categoryId;
                }

                $this->proceedData($data, $categoryId);
            }
        }
        fclose($handle);
    }
    private function proceedData(array $data, int $categoryId): void
    {

        $question = $data[1];
        $explanation = $data[2];
        $answers = [];
        for ($i = 3; $i < count($data) ; $i+=2) {
            $answers[$data[$i]] = (int)$data[$i+1];
        }
        $questionId = $this->questionDBHandler->create(['category_id'=> $categoryId,
                                                        'user_id'=>$_SESSION['UserId'],
                                                        'text' => $question]);

        foreach ($answers as $key => $answer){
            $answerId = $this->answerDBHandler->create(['text' => $key]);
            $this->relationDBHandler->create(['question_id' => $questionId,'answer_id' => $answerId,'is_right'=>$answer]);
        }
    }

    function writeCSV(string $fileName, array $questionIds): void
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename='.$fileName);

        $description='Category,Question,Explanation,Answer,isRight,Answer,isRight,...';

        $fp = fopen($fileName, 'wb');
        $val = explode(",", $description);
        fputcsv($fp, $val);

        foreach ($questionIds as $questionId ) {
            $questionData = $this->factory->createQuizQuestionById($questionId);
            $preparedData = [];
            $preparedData[] = $questionData->getCategory()->getText();
            $preparedData[] = $questionData->getText();
            $preparedData[] = ' ';
            $relationData = $this->relationDBHandler->findById($questionData->getId());
            foreach ($relationData as $relation){
                $preparedData[] = $this->factory->findIdTextObjectById((int) $relation['answer_id'],KindOf::ANSWER)->getText();
                $preparedData[] = $relation['is_right'];
            }
            fputcsv($fp, $preparedData);
        }
        fclose($fp);
    }
}