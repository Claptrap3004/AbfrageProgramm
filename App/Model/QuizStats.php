<?php

namespace quiz;

class QuizStats
{
    private Factory $factory;
    private QuizContentDBHandler $dbHandler;
    private array $questions;
    private array $validatedQuestions = [];
    private int $questionsAsked = 0;
    private int $answeredCorrect = 0;


    public function __construct()
    {
        $this->factory = Factory::getFactory();
        $this->dbHandler = new QuizContentDBHandler(KindOf::QUIZCONTENT);
        $this->getQuestionsFromDB();
        $this->validate();
//        var_dump($this->questions);
    }


    // loads Questions from actual users quiz_content table in db, as well as the given answers
    // stored in track_quiz_content table in db

    private function getQuestionsFromDB():void
    {
        $answeredQuestionsData = $this->dbHandler->findAll();
        $this->questionsAsked = count($answeredQuestionsData);
        foreach ($answeredQuestionsData as $questionData) {
            $question = $this->factory->createQuizQuestionById($questionData['id']);
            $answersData = $this->dbHandler->findById($questionData['id']);
            $answers = [];
            foreach ($answersData as $answer) $answers[] = $this->factory->findIdTextObjectById($answer['answer_id'], KindOf::ANSWER);
            $question->setGivenAnswers($answers);
            $this->questions[] = $question;
        }
    }

    // calls validation method inside QuizQuestion and maps each question id to bool value in validatedQuestions array
    // as well as to increment counter for tracking the number of correctly answered questions

    private function validate(): void
    {
        foreach ($this->questions as $question) {
            $key = $question->getId();
            $value = 0;
            if ($question->validate()){
                $this->answeredCorrect++;
                $value = 1;
            }
            $this->validatedQuestions["$key"] = $value;
        }

    }

    // provides the success rate of the quiz

    public function getRate():float
    {
        $percentage = $this->questionsAsked != 0 ? $this->answeredCorrect * 100 / $this->questionsAsked : 0;
        return round($percentage,2);
    }

    public function getQuestions(): array
    {
        return $this->questions;
    }

    public function getValidatedQuestions(): array
    {
        return $this->validatedQuestions;
    }

    public function getQuestionsAsked(): int
    {
        return $this->questionsAsked;
    }

    public function getAnsweredCorrect(): int
    {
        return $this->answeredCorrect;
    }


}
