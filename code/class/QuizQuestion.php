<?php

namespace quiz;

class QuizQuestion extends Question
{
    private Stats $stats;
    private array $givenAnswers;

    /**
     * @param Stats $stats
     */
    public function __construct(int $id, string $text,IdText $category, array $rightAnswers, array $wrongAnswers,Stats $stats)
    {
        parent::__construct($id,$text,$category,$rightAnswers,$wrongAnswers);
        $this->stats = $stats;
        $this->givenAnswers = [];
    }

    public function validate()
    {
        // track stats
        // provide result

    }

}