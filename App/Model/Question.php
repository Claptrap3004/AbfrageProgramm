<?php
// deals as parent class for EditQuestion and QuizQuestion class


namespace quiz;
abstract class Question extends IdText
{
    protected IdText $category;
    protected array $rightAnswers;
    protected array $wrongAnswers;


    /**
     * @param int $id
     * @param string $text
     * @param IdText $category
     * @param IdText[] $rightAnswers
     * @param IdText[] $wrongAnswers
     */
    protected function __construct(int $id, string $text, IdText $category, array $rightAnswers, array $wrongAnswers)
    {
        parent::__construct($id, $text, KindOf::QUESTION);
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

    /** returns shuffled merge of right and wrong answer arrays
     * @return array
     */
    public function getAnswers():array
    {
        $answers = array_merge($this->rightAnswers,$this->wrongAnswers);
        shuffle($answers);
        return $answers;
    }

}