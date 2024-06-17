<?php

namespace quiz;

class QuizQuestion extends Question
{
    private Stats $stats;
    private array $givenAnswers;

    /**
     * @param int $id
     * @param string $text
     * @param IdText $category
     * @param IdText[] $rightAnswers
     * @param IdText[] $wrongAnswers
     * @param Stats $stats
     */
    public function __construct(int $id, string $text,IdText $category, array $rightAnswers, array $wrongAnswers,Stats $stats)
    {
        parent::__construct($id,$text,$category,$rightAnswers,$wrongAnswers);
        $this->stats = $stats;
        $this->givenAnswers = [];
    }

    public function validate(): bool
    {
        // provide result
        // track stats
        usort($this->givenAnswers, fn($a, $b) => strcmp($a->id, $b->id));
        usort($this->rightAnswers, fn($a, $b) => strcmp($a->id, $b->id));
        $this->stats->incrementTimesAsked();
        if ($this->givenAnswers == $this->rightAnswers){
            $this->stats->incrementTimesRight();
            return true;
        }
        return false;
    }

    public function getStats(): Stats
    {
        return $this->stats;
    }

    public function getGivenAnswers(): array
    {
        return $this->givenAnswers;
    }

    public function addGivenAnswer(IdText $answer): void
    {
        if (!$this->existsInGivenAnswers($answer))
            $this->givenAnswers[] = $answer;
    }

    public function removeGivenAnswer(IdText $answer): void
    {
        foreach ($this->givenAnswers as $index => $givenAnswer){
            if ($givenAnswer->equals($answer)) {
                unset($index, $this->givenAnswers);
                $this->givenAnswers = array_values($this->givenAnswers);
                break;
            }
        }
    }

    private function existsInGivenAnswers(IdText $answer): bool
    {
        foreach ($this->givenAnswers as $givenAnswer)
            if ($givenAnswer.$this->equals($answer)) return true;
        return false;
    }

}