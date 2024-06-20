<?php
// needs to be optimzed, maybe mor stub and mock
namespace quiz;
include 'classIncludes.php';
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
    private CanConnectDB $connector;

    private Stats $stats;
    public function setUp(): void
    {
        include_once 'rebuildTestDB.php';
        $this->connector = new MariaDBConnector('test');
        $this->id = 1;
        $this->text = 'Welches Bauteil eines Computers führt Berechnungen durch ?';
        $this->answer1 = new IdText(1,'CPU',KindOf::ANSWER, $this->connector);
        $this->answer2 = new IdText(2,'Northbridge',KindOf::ANSWER, $this->connector);
        $this->answer3 = new IdText(3,'RAM',KindOf::ANSWER, $this->connector);
        $this->answer4 = new IdText(15,'USB - Port',KindOf::ANSWER, $this->connector);
        $this->testRightAnswers[] = $this->answer1;
        $this->testWrongAnswers[] = $this->answer2;
        $this->testWrongAnswers[] = $this->answer3;
        $this->testWrongAnswers[] = $this->answer4;
        $this->testGivenAnswers[] = $this->answer1;

        $this->category = new IdText(1,'PC - Grundlagen', KindOf::CATEGORY, $this->connector, $this->connector);
        $this->stats = new Stats(1,2,1, $this->connector);
        $this->testQuestion = new QuizQuestion($this->id,$this->text, $this->connector,$this->category, $this->testRightAnswers,$this->testWrongAnswers, $this->stats);

    }

    public function testFindAllIdTextObject()
    {
        $factory = new Factory();
        $assertion = [
            new IdText(1,'PC - Grundlagen', KindOf::CATEGORY, $this->connector),
            new IdText(2,'Netzwerk', KindOf::CATEGORY, $this->connector),
            new IdText(3,'Datenbank', KindOf::CATEGORY, $this->connector),
            new IdText(4,'Programmierung', KindOf::CATEGORY, $this->connector),
            new IdText(5,'Projektmanagement', KindOf::CATEGORY, $this->connector),
            new IdText(6,'Betriebssysteme', KindOf::CATEGORY, $this->connector)
            ];

        $act = $factory->findAllIdTextObject(KindOf::CATEGORY);
        $this->assertEqualsCanonicalizing($assertion, $act);
    }

    public function testCreateIdTextObject()
    {
        $assert = new IdText(7,'text', KindOf::CATEGORY, $this->connector);
        $arrange = new Factory();
        $act = $arrange->createIdTextObject('text',KindOf::CATEGORY);
        $this->assertEquals($assert, $act);
    }

    public function testFindIdTetObjectById()
    {
        $this->category = new IdText(2,'Netzwerk', KindOf::CATEGORY, $this->connector);
        $arrange = new Factory();
        $act = $arrange->findIdTextObjectById(2, KindOf::CATEGORY);
        $this->assertEquals($this->category, $act);
    }

    public function testCreateQuizQuestionById()
    {
        $this->id = 1;
        $this->text = 'Welches Bauteil eines Computers führt Beechnungen durch ?';
        $this->answer1 = new IdText(1,'CPU',KindOf::ANSWER, $this->connector);
        $this->answer2 = new IdText(2,'Northbridge',KindOf::ANSWER, $this->connector);
        $this->answer3 = new IdText(3,'RAM',KindOf::ANSWER, $this->connector);
        $this->answer4 = new IdText(4,'USB - Port',KindOf::ANSWER, $this->connector);
        $this->testRightAnswers = [];
        $this->testWrongAnswers = [];
        $this->testRightAnswers[] = $this->answer1;
        $this->testWrongAnswers[] = $this->answer2;
        $this->testWrongAnswers[] = $this->answer3;
        $this->testWrongAnswers[] = $this->answer4;

        $this->category = new IdText(1,'PC - Grundlagen', KindOf::CATEGORY, $this->connector);
        $this->testQuestion = new QuizQuestion($this->id,$this->text, $this->connector,$this->category, $this->testRightAnswers,$this->testWrongAnswers, $this->stats);

        $arrange = new Factory();
        $act = $arrange->createQuizQuestionById(1);
        $this->assertEquals($this->testQuestion, $act);
    }

    public function testCreateStatsByQuestionId()
    {
        $assert = new Stats(1,2,1, $this->connector);
        $arrange = new Factory();
        $act = $arrange->crateStatsByQuestionId(1);
        $this->assertEquals($assert,$act);
    }
}
