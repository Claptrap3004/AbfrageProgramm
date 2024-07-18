<?php

namespace quiz;

class QuizStats
{
    private Factory $factory;
    private QuizContentDBHandler $dbHandler;
    private array $questions = [];
    private array $validatedQuestions = [];
    private int $questionsAsked = 0;
    private int $answeredCorrect = 0;
    private array $questionData = [];


    public function __construct()
    {
        $this->factory = Factory::getFactory();
        $this->dbHandler = KindOf::QUIZCONTENT->getDBHandler();
        $this->getQuestionsFromDB();
        $this->validate();
    }


    /**
     * loads questions from actual users quiz_content table in db, as well as the given answers stores in
     * track_quiz_content table in db
     * @return void
     */
    private function getQuestionsFromDB(): void
    {
        $answeredQuestionsData = $this->dbHandler->findAll();
        $this->questionsAsked = count($answeredQuestionsData);
        foreach ($answeredQuestionsData as $questionData) {
            try {
                $question = $this->factory->createQuizQuestionById($questionData['question_id']);
                $answersData = $this->dbHandler->findById($questionData['question_id']);
                $answers = [];
                foreach ($answersData as $answer) $answers[] = $this->factory->findIdTextObjectById($answer['answer_id'], KindOf::ANSWER);
                $question->setGivenAnswers($answers);
                $this->questions[] = $question;
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * calls validation method inside QuizQuestion and maps each question id to bool value in validatedQuestions array
     * as well as to increment counter for tracking the number of correctly answered questions
     * @return void
     */
    private function validate(): void
    {
        foreach ($this->questions as $question) {
            $key = $question->getId();
            $value = 0;
            if ($question->validate()) {
                $this->answeredCorrect++;
                $value = 1;
            }
            $this->validatedQuestions["$key"] = $value;
            $this->extractData($question);
        }
    }


    /**
     * populates questionData array with necessary infos stored in associative arrays as well as prepares json string
     * to be able to provide extra infos on finalStats page for each question
     * @param QuizQuestion $question
     * @return void
     */
    private function extractData(QuizQuestion $question): void
    {
        $key = $question->getId();
        $text = $question->getText();
        $explanation = $question->getExplanation();
        $answers = [];
        foreach ($question->getRightAnswers() as $answer) {
            $answerId = $answer->getId();
            $answers["$answerId"] = $this->createAssocArray($question, $answer, true);
        }
        foreach ($question->getWrongAnswers() as $answer) {
            $answerId = $answer->getId();
            $answers["$answerId"] = $this->createAssocArray($question, $answer, false);
        }
        $this->questionData[] =
            [
                'questionId' => $key,
                'isCorrect' => $this->validatedQuestions["$key"],
                'text' => $text,
                'explanation' => $explanation,
                'answers' => $answers
            ];

    }

    /**
     * provides associative arrays for each question in quiz containing info about answer text, if answer is correct
     * answer for given question and if the user selected the question to provide info on finalStats page
     * @param QuizQuestion $question
     * @param IdText $answer
     * @param bool $setTo
     * @return array
     */
    private function createAssocArray(QuizQuestion $question, IdText $answer, bool $setTo): array
    {
        $isSelected = ($question->existsInGivenAnswers($answer)) ? 'true' : 'false';
        return ['text' => $answer->getText(),
            'isRight' => ($setTo) ? 'true' : 'false',
            'isSelected' => $isSelected];

    }

    public function getQuestionData(): array
    {
        return $this->questionData;
    }

    public function getJSFormattedQuestionData():string
    {

        return json_encode($this->questionData);
    }


    public function getRate(): float
    {
        $percentage = $this->questionsAsked != 0 ? $this->answeredCorrect * 100 / $this->questionsAsked : 0;
        return round($percentage, 2);
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

    /**
     * prepares all collected stats necessary for output on finalStats page
     * @return array
     */
    public function getFormatted(): array
    {


        return ['asked' => $this->questionsAsked,
            'correct' => $this->answeredCorrect,
            'rate' => $this->getRate(),
             'questionsJS' => $this->getJSFormattedQuestionData()];
    }


}
