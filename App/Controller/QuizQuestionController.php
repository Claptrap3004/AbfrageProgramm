<?php

namespace quiz;

class QuizQuestionController extends Controller
{

    public function index(array $data = []):void
    {


    }

    public function answer(int $id): void
    {
        $factory = Factory::getFactory();
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $answers = $_POST['answers'] ?? [];
            $questionId = $_POST['questionId'] ?? $id;
            $question = $factory->createQuizQuestionById($questionId);
            foreach ($answers as $answer){
                $question->addGivenAnswer($factory->findIdTextObjectById($answer,KindOf::ANSWER));
            }
                $question->writeResultDB();
                header("refresh:1;url='https://abfrageprogramm.ddev.site:8443/user/show/$id'");
            } else {
        $question = $factory->createQuizQuestionById($id);
        if ($question) $this->view('quiz/answerQuestion',['question'=>$question]);
        }
    }

    public function next(int $id):void
    {

    }
}