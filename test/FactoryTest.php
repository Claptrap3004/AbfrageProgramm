<?php

namespace quiz;
include '../code/class/CanHandleDB.php';
include '../code/class/MariaDBConnector.php';
include '../code/class/IdTextDBHandler.php';
include '../code/class/KindOf.php';
include_once '../code/class/IdText.php';
include_once '../code/class/CanConnectDB.php';
include_once '../code/class/QuizQuestion.php';
include_once '../code/class/Question.php';
include_once '../code/class/Stats.php';
include_once '../code/class/Factory.php';
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
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
        $this->answer1 = new IdText(1,'Bla',KindOf::ANSWER);
        $this->answer2 = new IdText(2,'BlaBla',KindOf::ANSWER);
        $this->answer3 = new IdText(3,'BlaBlaBla',KindOf::ANSWER);
        $this->answer4 = new IdText(15,'BlaBlub',KindOf::ANSWER);
        $this->testRightAnswers[] = $this->answer1;
        $this->testRightAnswers[] = $this->answer2;
        $this->testWrongAnswers[] = $this->answer3;
        $this->testWrongAnswers[] = $this->answer4;
        $this->testGivenAnswers[] = $this->answer1;
        $this->testGivenAnswers[] = $this->answer2;
        $this->category = new IdText(9,'test', KindOf::CATEGORY);
        $this->stats = new Stats(1,0,0);
        $this->testQuestion = new QuizQuestion($this->id,$this->text,$this->category, $this->testRightAnswers,$this->testWrongAnswers, $this->stats);

    }

    public function testFindAllIdTextObject()
    {
        $factory = new Factory();
        $assertion = [
            new IdText(1,'PC - Grundlagen', KindOf::CATEGORY),
            new IdText(2,'Netzwerk', KindOf::CATEGORY),
            new IdText(3,'Datenbank', KindOf::CATEGORY),
            new IdText(4,'Programmierung', KindOf::CATEGORY),
            new IdText(5,'Projektmanagement', KindOf::CATEGORY),
            new IdText(6,'Betriebssysteme', KindOf::CATEGORY)
            ];

        $act = $factory->findAllIdTextObject(KindOf::CATEGORY);
        $this->assertEqualsCanonicalizing($assertion, $act);
    }

    public function testCreateIdTextObject()
    {
        $arrange = new Factory();
        $act = $arrange->createIdTextObject('test',KindOf::CATEGORY);
        $this->assertEquals($this->category, $act);
    }

    public function testFindIdTextObjectById()
    {
        $this->category = new IdText(2,'Netzwerk', KindOf::CATEGORY);
        $arrange = new Factory();
        $act = $arrange->findIdTextObjectById(2, KindOf::CATEGORY);
        $this->assertEquals($this->category, $act);
    }
}
