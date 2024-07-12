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
        $handler = KindOf::QUIZCONTENT->getDBHandler();
        $questions = $handler->findAll();
        if (count($questions) === 0) {
            $user = $this->factory->createUser($_SESSION['UserId']);
            $stats = new UserStats($user);
            $this->view('welcome', ['user' => $user, 'stats' => $stats]);
        } else header("refresh:0.01;url='" . HOST . "QuizQuestion/actual'");
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
        header("refresh:0.01;url='" . HOST . "QuizQuestion/actual'");

    }

    /**
     * directs to category selection page, after categories and number of questions are selected QuestionSelector object
     * is created and random selection of question ids is sent to according method to create the content
     */
    public function select(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $categories = $_POST['categories'] ?? [];
            $numberOfQuestions = $_POST['range'] ?? 0;
            $selector = new QuestionSelector();
            $questions = $selector->select($numberOfQuestions, $categories);
            $this->fillTableAndStartActual($questions);
        } else {
            $questionsByCategories = KindOf::QUESTION->getDBHandler()->findAll(['question_by_category' => null]);
            $this->view('quiz/selectQuestions', ['categories' => $questionsByCategories]);
        }
    }


    /**
     * loads question for given id with possible answers and displays it. On post request of page it sets actual attribute
     * in quiz_content table after storing given answer(s) in track_quiz_content table. If post request is set next question
     * as actual while actual question was last one is_actual is set to false for all questions to trigger validation of
     * quiz.
     * @param int $id
     * @return void
     */
    public function answer(int $id): void
    {

        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $clearStats = $_POST['clearStats'] ?? '';
            $answers = $_POST['answers'] ?? [];
            $questionId = $_POST['questionId'] ?? $id;

            try {
                $question = $this->factory->createQuizQuestionById($questionId);
                if ($clearStats) $question->getStats()->reset();
                foreach ($answers as $answer) {
                    if ((int)$answer > 0) $question->addGivenAnswer($this->factory->findIdTextObjectById((int)$answer, KindOf::ANSWER));
                }

                $question->writeResultDB();

                if (isset($_POST['finish'])) $whichActual = SetActual::NONE;
                else $whichActual = isset($_POST['setNext']) ? SetActual::NEXT : SetActual::PREVIUOS;

                KindOf::QUIZCONTENT->getDBHandler()->setActual($whichActual);

            } catch (\Exception $e) {
                if (KindOf::QUIZCONTENT->getDBHandler()->getActualQuestionId() === $id) KindOf::QUIZCONTENT->getDBHandler()->deleteAtId($id);
            }
        } else {
            try {
                $question = $this->factory->createQuizQuestionById($id);
                $trackContent = KindOf::QUIZCONTENT->getDBHandler()->findById($id);
                $answers = [];
                foreach ($trackContent as $item) $answers[] = $item['answer_id'];
                $content = $this->getContentInfos();
                $this->view('quiz/answerQuestion', ['question' => $question, 'answers' => $answers, 'contentInfo' => $content]);
            } catch (\Exception $e) {
                if (KindOf::QUIZCONTENT->getDBHandler()->getActualQuestionId() === $id) KindOf::QUIZCONTENT->getDBHandler()->deleteAtId($id);
            }
        }
        $this->actual();
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

    /** checks for actual question in quiz_content and calls answer method for given question id. if no item is set to
     * actual in quiz_content validation and stats are triggered.
     * @return void
     */
    public function actual(): void
    {
        $id = KindOf::QUIZCONTENT->getDBHandler()->getActualQuestionId();
        if ($id) header("refresh:0.01;url='" . HOST . "QuizQuestion/answer/$id'");
        else {
            header("refresh:0.01;url='" . HOST . "QuizQuestion/final'");
        }
    }

    public function final(): void
    {
        $quizStats = new QuizStats();
        KindOf::QUIZCONTENT->getDBHandler()->createTables();
        $this->view('quiz/finalStats', $quizStats->getFormatted());
    }

    public function quickStart($numberOfQuestions = 20): void
    {
        $selector = new QuestionSelector();
        $questions = $selector->select($numberOfQuestions);
        $this->fillTableAndStartActual($questions);
    }

    public function test(): void
    {
        $this->view('base', ['file' => 'qmarks.jpg']);
    }


}