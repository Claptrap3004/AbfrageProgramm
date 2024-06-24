<?php
require 'public/automateInclude.php';
use PHPUnit\Framework\TestCase;
use quiz\CanConnectDB;
use quiz\EditQuestion;
use quiz\IdText;
use quiz\KindOf;
use quiz\MariaDBConnector;
use quiz\Stats;

class EditQuestionTest extends TestCase
{
    private EditQuestion $question;

    public function setUp(): void
    {
        include_once 'rebuildTestDB.php';
        $connector = new MariaDBConnector('test');
        $category = new IdText(1,'PC - Grundlagen', KindOf::CATEGORY,$connector);
        $text = 'Welches Bauteil eines Computers führt Berechnungen durch ?';

        $answer1 = new IdText(1,'CPU',KindOf::ANSWER, $connector);
        $answer2 = new IdText(2,'Northbridge',KindOf::ANSWER, $connector);
        $answer3 = new IdText(3,'RAM',KindOf::ANSWER, $connector);
        $answer4 = new IdText(4,'USB - Port',KindOf::ANSWER, $connector);
        $wrongs = [$answer2,$answer3,$answer4];
        $rights = [$answer1];
        $this->question = new EditQuestion(1, $text,$connector,$category,$rights,$wrongs);

    }

    public function testRelationMapper()
    {
        $assertion = [
            1 => ['id' => 1,'question_id' => 1, 'answer_id' => 1, 'is_right' => 1],
            2 => ['id' => 2,'question_id' => 1, 'answer_id' => 2, 'is_right' => 0],
            3 => ['id' => 3,'question_id' => 1, 'answer_id' => 3, 'is_right' => 0],
            4 => ['id' => 4,'question_id' => 1, 'answer_id' => 4, 'is_right' => 0]
            ];
        $act = $this->question->getRelationMapper();
        $this->assertEquals($assertion, $act);
    }


    public function testSaveQuestionAssertRight()
    {
        $message = '';
        $assertion = '';
        try {
            $this->question->saveQuestion();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $this->assertEquals($assertion,$message);

    }

    public function testSaveQuestionAssertWrong()
    {
        $message = '';
        $assertion = '';
        $answer3 = new IdText(3,'RAM',KindOf::ANSWER, new MariaDBConnector('test'));
        $this->question->removeAnswer($answer3);
        try {
            $this->question->saveQuestion();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $this->assertNotEquals($assertion,$message);

    }


    public function testDelete()
    {

    }

    public function testSetAnswerRelation()
    {
        $answer5 = new IdText(9,'CREATE', KindOf::ANSWER, new MariaDBConnector('test'));
        $this->question->setAnswerRelation($answer5,false);
        $act = $this->question->getRelationMapper();
        $assertion = [
            1 => ['id' => 1,'question_id'=>1, 'answer_id' => 1, 'is_right' => 1],
            2 => ['id' => 2,'question_id'=>1, 'answer_id' => 2, 'is_right' => 0],
            3 => ['id' => 3,'question_id'=>1, 'answer_id' => 3, 'is_right' => 0],
            4 => ['id' => 4,'question_id'=>1, 'answer_id' => 4, 'is_right' => 0],
            9 => ['id' => null,'question_id'=>1, 'answer_id' => 9, 'is_right' => 0]
        ];
        $this->assertEquals($assertion, $act);

    }

    public function testRemoveAnswer()
    {
        $assertion = [
            1 => ['id' => 1,'question_id'=>1, 'answer_id' => 1, 'is_right' => 1],
            2 => ['id' => 2,'question_id'=>1, 'answer_id' => 2, 'is_right' => 0],
            4 => ['id' => 4,'question_id'=>1, 'answer_id' => 4, 'is_right' => 0]];
        $answer3 = new IdText(3,'RAM',KindOf::ANSWER, new MariaDBConnector('test'));
        $this->question->removeAnswer($answer3);
        $act = $this->question->getRelationMapper();
        $this->assertEquals($assertion, $act);

    }
}
