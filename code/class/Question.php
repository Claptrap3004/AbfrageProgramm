<?php
// deals as parent class for EditQuestion and QuizQuestion class

namespace quiz;

abstract class Question extends IdText
{
    private IdText $category;
    private array $rightAnswers;
    private array $wrongAnswers;


    /**
     * @param int $id
     * @param string $text
     * @param IdText $category
     * @param IdText[] $rightAnswers
     * @param IdText[] $wrongAnswers
     */
    public function __construct(int $id, string $text,IdText $category, array $rightAnswers, array $wrongAnswers)
    {
        parent::__construct($id, $text, KindOfIdText::QUESTION);
        $this->category = $category;
        $this->rightAnswers = $rightAnswers;
        $this->wrongAnswers = $wrongAnswers;
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


}