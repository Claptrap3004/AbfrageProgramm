<?php

namespace quiz;

use Random\RandomException;

class QuestionSelector implements CanSelectQuestions
{
    private array $questionPool;

    /**
     * @throws RandomException
     *
     */
    public function select(int $numberOfQuestions, array $categoryIds = []): array
    {
        $questions = [];
        echo 'categoryIds Array : <br>';var_dump($categoryIds);

        $this->questionPool = KindOf::QUESTION->getDBHandler()->findAll(Filters::CATEGORY->createArray($categoryIds));
        var_dump($this->questionPool);
        if ($numberOfQuestions > count($this->questionPool)) return [];
        for ($i = 0; $i < $numberOfQuestions; $i++) {
            $questions[] = $this->pickOne();
        }
        return $questions;
    }

    /**
     * @throws RandomException
     */
    private function pickOne() : int
    {
        $index = random_int(0,count($this->questionPool)-1);
        $id = $this->questionPool[$index]['id'];
        unset( $this->questionPool[$index]);
        $this->questionPool = array_values($this->questionPool);
        return $id;
    }

}