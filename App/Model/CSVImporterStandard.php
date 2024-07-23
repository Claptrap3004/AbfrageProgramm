<?php

namespace quiz;

use Exception;

class CSVImporterStandard implements CanHandleCSV
{
    private DBFactory $dbFactory;

    private CanHandleDB $relationDBHandler;
    private Factory $factory;

    public function __construct()
    {
        $this->relationDBHandler = KindOf::RELATION->getDBHandler();
        $this->factory = Factory::getFactory();
        $this->dbFactory = DBFactory::getFactory();
    }


    function readCSV(string $fileName, string $separator = '@' ): void
    {
        $row = 0;
        if (($handle = fopen($fileName, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, $separator)) !== FALSE) {
                $row++;
                if ($row === 1) continue;

                $this->proceedData($data);
            }
        }
        fclose($handle);
    }

    private function proceedData(array $data): void
    {
        $category = $this->restoreLineBreaks($data[0]);
        $question = $this->restoreLineBreaks($data[1]);
        $explanation = $this->restoreLineBreaks($data[2]);;
        $answers = [];
        for ($i = 3; $i < count($data)-1; $i += 2) {
            $answers[$data[$i]] = (int)$data[$i + 1];
        }
        try {
            $this->dbFactory->createQuizQuestionByCSVImport($question, $category,$explanation, $answers);
        } catch (Exception $e) {
            echo $e;
            return;
        }
    }

    public function restoreLineBreaks(string $data):string
    {
        return str_replace('<br>', "\n",$data);
    }

    function writeCSV(string $fileName, array $questionIds): void
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=' . $fileName);

        $description = 'Category,Question,Explanation,Answer,isRight,Answer,isRight,...';

        $fp = fopen($fileName, 'wb');
        $val = explode(",", $description);
        fputcsv($fp, $val);

        foreach ($questionIds as $questionId) {
            try {
                $questionData = $this->factory->createQuizQuestionById($questionId);
            } catch (Exception $e) {
                continue;
            }
            $preparedData = [];
            $preparedData[] = $questionData->getCategory()->getText();
            $preparedData[] = $questionData->getText();
            $preparedData[] = $questionData->getExplanation();
            $relationData = $this->relationDBHandler->findById($questionData->getId());
            foreach ($relationData as $relation) {
                $preparedData[] = $this->factory->findIdTextObjectById((int)$relation['answer_id'], KindOf::ANSWER)->getText();
                $preparedData[] = $relation['is_right'];
            }
            fputcsv($fp, $preparedData);
        }
        fclose($fp);
    }
}