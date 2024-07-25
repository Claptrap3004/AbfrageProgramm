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

    public function welcome(): void
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
            $answers = $_POST['answers'] ?? [];
            $questionId = $_POST['questionId'] ?? $id;
            if ($id !== null) {

                try {
                    $question = $this->factory->createQuizQuestionById($questionId);

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
                $question->setGivenAnswers($answers);
                $jsData = json_encode($question);
                $content = new ContentInfos();
                $jsContent = json_encode($content);
                $this->view(UseCase::ANSWER_QUESTION->getView(), ['contentInfo' => $jsContent, 'jsData' => $jsData]);
            } catch (\Exception $e) {
                if (KindOf::QUIZCONTENT->getDBHandler()->getActualQuestionId() === $id) KindOf::QUIZCONTENT->getDBHandler()->deleteAtId($id);
                $this->answer();
            }
        }
    }

    public function final(): void
    {
        $quizStats = new QuizStatsView();
        $quizStatsView = json_encode($quizStats);

        if (isset($_REQUEST['reset'])) {
            KindOf::QUIZCONTENT->getDBHandler()->setActual(SetActual::FIRST);
            $this->answer();
        } elseif (isset($_REQUEST['confirm'])) {
            $_SESSION['final'] = true;
            $quizStats->validate();
            $quizStatsView = json_encode($quizStats);
            $this->view(UseCase::FINALIZE_QUIZ->getView(), ['questionsJS' => $quizStatsView]);
        } else {
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

    public function deleteStatsQuestion(): void
    {
        $id = $_POST['id'] ?? 0;
        KindOf::STATS->getDBHandler()->deleteAtId($id);
    }

    public function deleteStatsAll(): void
    {
        KindOf::STATS->getDBHandler()->deleteAll();
    }

    public function test(): void
    {

    }


}