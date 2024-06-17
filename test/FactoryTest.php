<?php

namespace quiz;
include '../code/class/CanHandleDB.php';
include '../code/class/MariaDBConnector.php';
include '../code/class/IdTextDBHandler.php';
include '../code/class/KindOfIdText.php';
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
        $this->answer1 = new IdText(1,'Bla',KindOfIdText::ANSWER);
        $this->answer2 = new IdText(2,'BlaBla',KindOfIdText::ANSWER);
        $this->answer3 = new IdText(3,'BlaBlaBla',KindOfIdText::ANSWER);
        $this->answer4 = new IdText(15,'BlaBlub',KindOfIdText::ANSWER);
        $this->testRightAnswers[] = $this->answer1;
        $this->testRightAnswers[] = $this->answer2;
        $this->testWrongAnswers[] = $this->answer3;
        $this->testWrongAnswers[] = $this->answer4;
        $this->testGivenAnswers[] = $this->answer1;
        $this->testGivenAnswers[] = $this->answer2;
        $this->category = new IdText(9,'test', KindOfIdText::CATEGORY);
        $this->stats = new Stats(1,0,0);
        $this->testQuestion = new QuizQuestion($this->id,$this->text,$this->category, $this->testRightAnswers,$this->testWrongAnswers, $this->stats);

    }

    public function testFindAllIdTextObject()
    {
        $factory = new Factory();
        $assertion = [
            new IdText(1,'PC - Grundlagen', KindOfIdText::CATEGORY),
            new IdText(2,'Netzwerk', KindOfIdText::CATEGORY),
            new IdText(3,'Datenbank', KindOfIdText::CATEGORY),
            new IdText(4,'Programmierung', KindOfIdText::CATEGORY),
            new IdText(5,'Projektmanagement', KindOfIdText::CATEGORY),
            new IdText(6,'Betriebssysteme', KindOfIdText::CATEGORY)
            ];

        $act = $factory->findAllIdTextObject(KindOfIdText::CATEGORY);
        $this->assertEqualsCanonicalizing($assertion, $act);
    }

    public function testCreateIdTextObject()
    {
        $arrange = new Factory();
        $act = $arrange->createIdTextObject('test',KindOfIdText::CATEGORY);
        $this->assertEquals($this->category, $act);
    }

    public function testFindIdTextObjectById()
    {
        $this->category = new IdText(2,'Netzwerk', KindOfIdText::CATEGORY);
        $arrange = new Factory();
        $act = $arrange->findIdTextObjectById(2, KindOfIdText::CATEGORY);
        $this->assertEquals($this->category, $act);
    }
}
