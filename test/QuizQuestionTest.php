<?php

namespace quiz;
// require __DIR__ . '/../vendor/autoload.php';
include 'classIncludes.php';
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
    private CanConnectDB $connector;

    private Stats $stats;
    public function setUp(): void
    {
        $this->connector = new MariaDBConnector();
        $this->id = 1;
        $this->text = 'blabla';
        $this->answer1 = new IdText(1,'Bla',KindOf::ANSWER, $this->connector);
        $this->answer2 = new IdText(2,'BlaBla',KindOf::ANSWER, $this->connector);
        $this->answer3 = new IdText(3,'BlaBlaBla',KindOf::ANSWER, $this->connector);
        $this->answer4 = new IdText(4,'BlaBlub',KindOf::ANSWER, $this->connector);
        $this->testRightAnswers[] = $this->answer1;
        $this->testRightAnswers[] = $this->answer2;
        $this->testWrongAnswers[] = $this->answer3;
        $this->testWrongAnswers[] = $this->answer4;
        $this->testGivenAnswers[] = $this->answer1;
        $this->testGivenAnswers[] = $this->answer2;
        $this->category = new IdText(1,'test', KindOf::CATEGORY, $this->connector);
        $this->stats = new Stats(1,2,1, $this->connector);
        $this->testQuestion = new QuizQuestion($this->id,$this->text, $this->connector,$this->category, $this->testRightAnswers,$this->testWrongAnswers, $this->stats);

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
