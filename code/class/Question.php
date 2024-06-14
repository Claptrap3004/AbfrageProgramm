<?php

namespace quiz;

class Question extends IdText
{
    private IdText $category;
    private array $rightAnswers;
    private array $wrongAnswers;
    private Stats $stats;

    /**
     * @param IdText $category
     * @param array $rightAnswers
     * @param array $wrongAnswers
     * @param Stats $stats
     */
    public function __construct(int $id, string $text,IdText $category, array $rightAnswers, array $wrongAnswers, Stats $stats)
    {
        parent::__construct($id, $text, KindOfIdText::QUESTION);
        $this->category = $category;
        $this->rightAnswers = $rightAnswers;
        $this->wrongAnswers = $wrongAnswers;
        $this->stats = $stats;
    }

    public function getCategory(): IdText
    {
        return $this->category;
    }

    public function getRightAnswers(): array
    {
        return $this->rightAnswers;
    }

    public function getWrongAnswers(): array
    {
        return $this->wrongAnswers;
    }

    public function getStats(): Stats
    {
        return $this->stats;
    }


}