<?php

namespace quiz;

class QuizQuestionController extends Controller
{

    /**
     * checks whether there is still an unfinished quiz. In case unfinished quiz exists user is directed to actual
     * question of quiz, else user is directed to welcome screen
     * @param array $data
     * @return void
     */
    public function index(array $data = []): void
    {
        if (isset($_SESSION['final'])) {
            KindOf::QUIZCONTENT->getDBHandler()->createTables();
            unset($_SESSION['final']);
        }
        $handler = KindOf::QUIZCONTENT->getDBHandler();
        $questions = $handler->findAll();
        if (count($questions) === 0) {
          $this->welcome();
        } else $this->answer();
    }

    public function welcome():void
    {
        $user = $this->factory->createUser($_SESSION['UserId']);
        $stats = new UserStats($user);
        $this->view(UseCase::WELCOME->getView(), ['user' => $user, 'stats' => $stats]);
    }
    /**
     * expects key 'question_ids' which holds int[] storing selected question_ids that will be asked in the quiz.
     * populates quiz_content table of actual user and directs to answer options of the first question
     * @param array $data
     * @return void
     */
    private function fillTableAndStartActual(array $data = []): void
    {
        $handler = KindOf::QUIZCONTENT->getDBHandler();
        $handler->create(['question_ids' => $data]);
        header("refresh:0.01;url='" . HOST . "QuizQuestion/answer'");

    }

    /**
     * directs to category selection page, after categories and number of questions are selected QuestionSelector object
     * is created and random selection of question ids is sent to according method to create the content
     * @return void
     */
    public function select(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $categories = $_POST['categories'] ?? [];
            $numberOfQuestions = (int)$_POST['range'] ?? 0;
            $selector = new QuestionSelector();
            $questions = $selector->select($numberOfQuestions, $categories);
            $this->fillTableAndStartActual($questions);
        } else {
            $questionsByCategories = KindOf::QUESTION->getDBHandler()->findAll(['question_by_category' => null]);
            $jsData = json_encode($questionsByCategories);
            $this->view('quiz/selectQuestions', ['categories' => $questionsByCategories, 'jsData' => $jsData]);
        }
    }


    /**
     * loads actual question with possible answers and displays it. On post request of page it sets actual attribute
     * in quiz_content table after storing given answer(s) in track_quiz_content table. If post request is set next question
     * as actual while actual question was last one is_actual is set to false for all questions to trigger validation of
     * quiz.
     * @return void
     */
    public function answer(): void
    {
        $id = KindOf::QUIZCONTENT->getDBHandler()->getActualQuestionId();
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
             $clearStats = $_POST['clearStats'] ?? '';
            $answers = $_POST['answers'] ?? [];
            $questionId = $_POST['questionId'] ?? $id;
            if ($id !== null) {

                try {
                    $question = $this->factory->createQuizQuestionById($questionId);
                    if ($clearStats) $question->getStats()->reset();
                    foreach ($answers as $answer) {
                        if ((int)$answer > 0) $question->addGivenAnswer($this->factory->findIdTextObjectById((int)$answer, KindOf::ANSWER));
                    }

                    $question->writeResultDB();

                    if (isset($_POST['finish'])) $whichActual = SetActual::NONE;
                    else $whichActual = isset($_POST['setNext']) ? SetActual::NEXT : SetActual::PREVIOUS;

                    KindOf::QUIZCONTENT->getDBHandler()->setActual($whichActual);

                } catch (\Exception $e) {
                    if (KindOf::QUIZCONTENT->getDBHandler()->getActualQuestionId() === $id) KindOf::QUIZCONTENT->getDBHandler()->deleteAtId($id);
                    $this->answer();
                }
            }
            unset($_POST);
            $_SERVER['REQUEST_METHOD'] = null;
            $id = KindOf::QUIZCONTENT->getDBHandler()->getActualQuestionId();
        }
        if (!$id) $this->final();
        else {
            try {
                $question = $this->factory->createQuizQuestionById($id);
                $trackContent = KindOf::QUIZCONTENT->getDBHandler()->findById($id);
                $answers = [];
                foreach ($trackContent as $item) $answers[] = $item['answer_id'];
                $content = $this->getContentInfos();
                $jsData = $this->jsFormat($question,$answers);
                $this->view(UseCase::ANSWER_QUESTION->getView(), ['question' => $question, 'answers' => $answers, 'contentInfo' => $content,'jsData'=> $jsData]);
            } catch (\Exception $e) {
                if (KindOf::QUIZCONTENT->getDBHandler()->getActualQuestionId() === $id) KindOf::QUIZCONTENT->getDBHandler()->deleteAtId($id);
                $this->answer();
            }
        }
    }

    private function jsFormat(QuizQuestion $question, array $answers): array{
        $data = [];
        $questionAnswers = [];
        foreach ($question->getAnswers() as $answer) $questionAnswers[]= $answer->getId();
        $data['givenAnswers'] = json_encode($answers);
        $data['questionAnswers'] = json_encode($questionAnswers);
        $stats = ['timesAsked' => $question->getStats()->getTimesAsked(), 'timesRight' => $question->getStats()->getTimesRight()];
        $data['stats'] = json_encode($stats);
        return $data;

    }

    private function getContentInfos(): array
    {
        $handler = KindOf::QUIZCONTENT->getDBHandler();
        $data = $handler->findAll();
        $id = null;
        foreach ($data as $item) {
            if ($item['is_actual']) $id = $item['id'];
        }
        return ['totalQuestions' => count($data), 'actual' => $id];
    }

    public function final(): void
    {
        $quizStatsView = json_encode(new QuizStatsView());

        if (isset($_REQUEST['reset'])) {
            KindOf::QUIZCONTENT->getDBHandler()->setActual(SetActual::FIRST);
            $this->answer();
        } elseif (isset($_REQUEST['confirm'])) {
            $_SESSION['final'] = true;
            $this->view(UseCase::FINALIZE_QUIZ->getView(), ['questionsJS' => $quizStatsView]);
        }
        else{
        $this->view(UseCase::CHECK_BEFORE_FINALIZE->getView(), ['questionsJS' => $quizStatsView]);
        }
        $_SERVER['REQUEST_METHOD'] = null;
    }

    public function quickStart($numberOfQuestions = 20): void
    {
        $selector = new QuestionSelector();
        $questions = $selector->select((int)$numberOfQuestions);
        $this->fillTableAndStartActual($questions);
    }

    public function test(): void
    {

    }


}