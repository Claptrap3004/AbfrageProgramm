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
        usort($this->givenAnswers, fn($a, $b) => strcmp($a->getId(), $b->getId()));
        usort($this->rightAnswers, fn($a, $b) => strcmp($a->getId(), $b->getId()));
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
        if ($this->givenAnswers == []) return;
        foreach ($this->givenAnswers as $index => $givenAnswer){
            if ($givenAnswer->equals($answer)) {
                unset($this->givenAnswers[$index]);
                $this->givenAnswers = array_values($this->givenAnswers);
                break;
            }
        }
    }

    private function existsInGivenAnswers(IdText $answer): bool
    {
        foreach ($this->givenAnswers as $givenAnswer)
            if ($givenAnswer->equals($answer)) return true;
        return false;
    }

    public function setGivenAnswers(array $givenAnswers): void
    {
        $this->givenAnswers = $givenAnswers;
    }

    public function writeResultDB(): void
    {
        $answerIds = [];
        foreach ($this->givenAnswers as $answer) $answerIds[]=$answer->getId();
        $handler = KindOf::QUIZCONTENT->getDBHandler();
        $handler->update(['question_id' => $this->id, 'answers' => $answerIds]);
    }

}