<?php

namespace quiz;
// require __DIR__ . '/../vendor/autoload.php';
include '../code/class/CanHandleDB.php';
include '../code/class/MariaDBConnector.php';
include '../code/class/IdTextDBHandler.php';
include '../code/class/KindOfIdText.php';
include_once '../code/class/IdText.php';
include_once '../code/class/CanConnectDB.php';
include_once '../code/class/QuizQuestion.php';
include_once '../code/class/Question.php';
include_once '../code/class/Stats.php';

use PHPUnit\Framework\TestCase;

class QuizQuestionTest extends TestCase
{
    private int $id;
    private string $text;
    private IdText $category;
    private IdText $answer1;
    private IdText $answer2;
    private IdText $answer3;
    private IdText $answer4;
    private array $testRightAnswers = [];
    private array $testWrongAnswers = [];
    private array $testGivenAnswers = [];
    private QuizQuestion $testQuestion;

    private Stats $stats;
    public function setUp(): void
    {
        $this->id = 1;
        $this->text = 'blabla';
        $this->answer1 = new IdText(1,'Bla',KindOfIdText::ANSWER);
        $this->answer2 = new IdText(2,'BlaBla',KindOfIdText::ANSWER);
        $this->answer3 = new IdText(3,'BlaBlaBla',KindOfIdText::ANSWER);
        $this->answer4 = new IdText(4,'BlaBlub',KindOfIdText::ANSWER);
        $this->testRightAnswers[] = $this->answer1;
        $this->testRightAnswers[] = $this->answer2;
        $this->testWrongAnswers[] = $this->answer3;
        $this->testWrongAnswers[] = $this->answer4;
        $this->testGivenAnswers[] = $this->answer1;
        $this->testGivenAnswers[] = $this->answer2;
        $this->category = new IdText(1,'test', KindOfIdText::CATEGORY);
        $this->stats = new Stats(1,0,0);
        $this->testQuestion = new QuizQuestion($this->id,$this->text,$this->category, $this->testRightAnswers,$this->testWrongAnswers, $this->stats);

    }

    public function testRemoveGivenAnswer()
    {
        $arrange = $this->testQuestion;
        $arrange->addGivenAnswer($this->answer1);
        $arrange->addGivenAnswer($this->answer2);
        $arrange->removeGivenAnswer($this->answer2);
        $act = $arrange->getGivenAnswers();

        $this->assertEqualsCanonicalizing([$this->answer1], $act);
    }

    public function testGetGivenAnswers()
    {
        $arrange = $this->testQuestion;
        $arrange->setGivenAnswers($this->testGivenAnswers);
        $act = $arrange->getGivenAnswers();
        $this->assertEqualsCanonicalizing($this->testRightAnswers, $act);
    }

    public function testGetStats()
    {
        $arrange = $this->testQuestion;
        $arrange->setGivenAnswers($this->testGivenAnswers);
        $arrange->validate();
        $act = $arrange->getStats();
        $this->assertEquals($this->stats->getTimesAsked(),1);

    }

    public function testAddGivenAnswer()
    {
        $arrange = $this->testQuestion;
        $arrange->addGivenAnswer($this->answer1);
        $arrange->addGivenAnswer($this->answer2);

        $act = $arrange->getGivenAnswers();
        $this->assertEqualsCanonicalizing($this->testRightAnswers, $act);
    }

    public function testValidate()
    {
        $arrange = $this->testQuestion;
        $arrange->setGivenAnswers($this->testGivenAnswers);

        $act = $arrange->validate();
        $this->assertTrue( $act);
    }
}
