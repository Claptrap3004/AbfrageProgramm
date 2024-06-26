<?php

namespace quiz;

class QuizQuestionController extends Controller
{

    public function index(array $data = []): void
    {
        $this->fillTable([1,2,3]);

    }
    public function fillTable(array $data = []): void
    {
        $handler = KindOf::QUIZCONTENT->getDBHandler();
        $handler->create(['question_ids' => $data]);
    }

    public function answer(int $id): void
    {
        $factory = Factory::getFactory();
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $answers = $_POST['answers'] ?? [];
            $questionId = $_POST['questionId'] ?? $id;
            $question = $factory->createQuizQuestionById($questionId);
            foreach ($answers as $answer) {
                if ((int)$answer > 0) $question->addGivenAnswer($factory->findIdTextObjectById((int)$answer, KindOf::ANSWER));
            }
            $question->writeResultDB();

            header("refresh:0.2;url='https://abfrageprogramm.ddev.site:8443/QuizQuestion/actual'");
        } else {
            $question = $factory->createQuizQuestionById($id);
            if ($question) $this->view('quiz/answerQuestion', ['question' => $question]);
        }
    }

    // checks quiz content for actual Question
    public function actual(): void
    {
        $handler = KindOf::QUIZCONTENT->getDBHandler();
        $data = $handler->findAll();
        $id = null;
        foreach ($data as $item){
            if ($item['is_actual']) $id = $item['question_id'];
        }
        if ($id) header("refresh:1;url='https://abfrageprogramm.ddev.site:8443/QuizQuestion/answer/$id'");
        else header("refresh:1;url='https://abfrageprogramm.ddev.site:8443/QuizQuestion/final'");
    }

    // redirects to page where user can decide whether to check all questions from beginning or
    // to get validation of quiz
    public function final():void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $dbhandler = KindOf::QUIZCONTENT->getDBHandler();
            if(isset ($_POST['confirm'])){
                $factory = Factory::getFactory();
                $answeredQuestionsData = $dbhandler->findAll();
                $questionsAsked = count($answeredQuestionsData);
                $questions = [];
                foreach ($answeredQuestionsData as $questionData){
                    $question = $factory->createQuizQuestionById($questionData['id']);
                    $answersData = $dbhandler->findById($questionData['id']);
                    $answers = [];
                    foreach ($answersData as $answer) $answers[] = $factory->findIdTextObjectById($answer['id'], KindOf::ANSWER);
                    $question->setGivenAnswers($answers);
                    $questions[] = $question;
                }
                $questionsRight = 0;
                foreach ($questions as $question){
                    if ($question->validate()) $questionsRight++;
                }
                $this->view('quiz/finalStats', ['total'=> $questionsAsked, 'right' => $questionsRight]);
            }
            else{
                $dbhandler->setActualFirst();
            }
        } else {
            $this->view('quiz/final', []);
        }
    }
}