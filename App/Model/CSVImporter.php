<?php

namespace quiz;

class CSVImporter
{
    public function __construct()
    {

    }

    public function readCSV(string $fileName): void
    {
        $categoryHandler = KindOf::CATEGORY->getDBHandler();
        $category = null;
        $categoryId = 0;
        $row = 0;
        if (($handle = fopen($fileName, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $row++;
                if ($row === 1) continue;
                if ($data[0] !== $category)  {
                    $categoryId = 0;
                    $category = $data[0];
                    $existingCategories = Factory::getFactory()->findAllIdTextObject(KindOf::CATEGORY);
                    foreach ($existingCategories as $existingCategory){
                        if ($existingCategory->getText() == $category) $categoryId = $existingCategory->getId();
                    }
                    $categoryId = $categoryId == 0 ? $categoryHandler->create(['text' => $category]) : $categoryId;
                }
                $this->proceedData($data, $categoryId);
                echo "Line $num proceeded";

            }
        }
        fclose($handle);
    }


    private function proceedData(array $data, int $categoryId): void
    {

        $question = $data[1];
        $answers = [];
        for ($i = 2; $i <= 5 ; $i++) {
        $answers[] = $data[$i];
        }
        $rightAnswer = $data[6];
        $description = $data[7];

        $questionDBHandler = KindOf::QUESTION->getDBHandler();
        $questionId = $questionDBHandler->create(['category_id'=> $categoryId,'user_id'=>2,'text' => $question]);
        $answerDBHandler = KindOf::ANSWER->getDBHandler();
        $relationDBHandler = KindOf::RELATION->getDBHandler();
        foreach ($answers as $answer){
            $isRight = $answer == $rightAnswer ? 1 : 0;
            $answerId = $answerDBHandler->create(['text' => $answer]);
            $relationDBHandler->create(['question_id' => $questionId,'answer_id' => $answerId,'is_right'=>$isRight]);
        }
    }
}

