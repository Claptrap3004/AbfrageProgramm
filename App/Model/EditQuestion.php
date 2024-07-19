<?php
namespace quiz;
include_once 'Question.php';
use Exception;


class EditQuestion extends Question
{
    private CanHandleDB $relator;

    // maps AnswerId($key) to RelationAttributesArray($value)
    // to crete a new relation an associative array whit values for question_id, answer_id and is_right needs to be
    // passed,  for update also answerToQuestion_id needs to be passed. To check if new relation needs to be created or
    // existing relation needs to be modified the id is set to null in relationMapper value when new key needs to be
    // created. When saving the edited question every entry in relationMapper is checked. If there is an id this entry
    // is updated in database, else new entry in database is going to be created.
    private array $relationMapper = [];
    private array $allAnswers = [];

    /**
     * @param int $id
     * @param string $text
     * @param string $explanation
     * @param IdText $category
     * @param IdText[] $rightAnswers
     * @param IdText[] $wrongAnswers
     */
    public function __construct(int $id, string $text,string $explanation, IdText $category, array $rightAnswers, array $wrongAnswers)
    {
        parent::__construct($id, $text,$explanation, $category, $rightAnswers, $wrongAnswers);
        $this->relator = KindOf::RELATION->getDBHandler();
        $this->allAnswers = array_merge($this->rightAnswers,$this->wrongAnswers);
        $this->setRelationMapper();
    }

    // gets all relation found for that question in database and creates an entry in relationMapper for each answerId
    // being found where the answerId is taken as key in mapper array
    private function setRelationMapper():void{
        $relations = $this->relator->findById($this->getId());
        foreach ($relations as $relation){
            $answerId = $relation['answer_id'];
            $this->relationMapper[$answerId] = $relation;

        }
    }

    public function getRelationMapper(): array
    {
        return $this->relationMapper;
    }


    // checks if relation to that answer already exists in mapper. If exists only the value for key 'is_right' is
    // changed, else new entry in mapper is created. In that case the 'id' value is set to null that the update
    // function can distinguish between new creates and updates in database
    public function setAnswerRelation(IdText $answer, bool $isRight): void
    {
        $answerId = $answer->getId();
        if (array_key_exists($answerId,$this->relationMapper))
            ($this->relationMapper[$answerId]['is_right'] = $isRight ? 1 : 0);
        else $this->relationMapper["$answerId"] = [
            'id' => null,
            'question_id' => $this->getId(),
            'answer_id' => $answerId,
            'is_right' => $isRight ? 1 : 0];
    }


    public function setCategory(IdText $category): void
    {
        $this->category = $category;
    }


    public function delete(EditQuestion $question):void
    {

    }

    // removes answer from allAnswers list as well as removing relation in db. With that approach risk of invalid
    // relations in db is minimized
    public function removeAnswer(IdText $answer): void
    {
        $answerId = $answer->getId();
        if (array_key_exists($answerId,$this->relationMapper)) {
            $relationId = $this->relationMapper[$answerId]['id'];
            $this->relator->deleteAtId($relationId);
            unset($this->relationMapper[$answerId]);
        }
        foreach ($this->allAnswers as $currentAnswer) if ($currentAnswer->equals($answer)) unset($currentAnswer);
        $this->allAnswers = array_values($this->allAnswers);
    }

    /**
     * checks if minimum of 4 choices / answers to questions is fulfilled, if not exception is thrown
     * @throws Exception
     */
    public function saveQuestion():void
    {
        if (count($this->relationMapper) >= 4) $this->update();
        else throw new Exception("Speichern der Frage nicht möglich, da weniger als 4 Antwortmöglichkeiten hinterlegt wurden");
    }

    // after check by saveQuestion the question text and category id are being updated in db, relations are either being
    // uodated or created
    protected function update(): void
    {
        $handler = $this->kindOf->getDBHandler();
        $handler->update(['id' => $this->id,
            'text' => $this->text,
            'category_id' => $this->category->getId()]);
        foreach ($this->relationMapper as $relation){
            if ($relation['id'] == null) $this->relator->create($relation);
            else $this->relator->update($relation);
        }
    }
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'category' => $this->category,
            'explanation' => $this->explanation,
            'rightAnswers' => $this->rightAnswers,
            'wrongAnswers' => $this->wrongAnswers,
        ];
    }


}