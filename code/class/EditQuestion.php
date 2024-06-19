<?php

namespace quiz;

use Exception;

class EditQuestion extends Question
{
    private CanHandleDB $relator;

    // maps AnswerId($key) to RelationAttributesArray($value)
    // to crete a new relation an associative array whit values for question_is, answer_id and is_right needs to be
    // passed,  for update also answerToQuestion_id needs to be passed
    private array $relationMapper = [];
    private array $allAnswers;

    /**
     * @param int $id
     * @param string $text
     * @param CanConnectDB $connector
     * @param IdText $category
     * @param IdText[] $rightAnswers
     * @param IdText[] $wrongAnswers
     */
    public function __construct(int $id, string $text, CanConnectDB $connector, IdText $category, array $rightAnswers, array $wrongAnswers)
    {
        parent::__construct($id, $text, $connector, $category, $rightAnswers, $wrongAnswers);
        $this->relator = KindOf::RELATION->getDBHandler($connector);
        $this->allAnswers = array_merge($this->rightAnswers,$this->wrongAnswers);
        $this->setRelationMapper();
    }
    private function setRelationMapper():void{
        $relations = $this->relator->findById($this->getId());
        foreach ($relations as $relation){
            $answerId = $relation['answer_id'];
            $this->relationMapper[$answerId] = $relation;
        }
    }

    public function setAnswerRelation(IdText $answer, bool $isRight): void
    {
        $answerId = $answer->getId();
        if (array_key_exists($answerId,$this->relationMapper))
            ($this->relationMapper[$answerId]['is_right'] = $isRight);
        else $this->relationMapper["$answerId"] = [
            'id' => null,
            'question_id' => $this->getId(),
            'answer_id' => $answerId,
            'is_right' => $isRight];
    }


    public function setCategory(IdText $category): void
    {
        $this->category = $category;
    }


    public function delete(EditQuestion $question):void
    {

    }
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
     * @throws Exception
     */
    public function saveQuestion():void
    {
        if ((count($this->rightAnswers) + count($this->wrongAnswers)) >= 4) $this->update();
        else throw new Exception("Speichern der Frage nicht möglich, da weniger als 4 Antwortmöglichkeiten hinterlegt wurden");
    }

    protected function update(): void
    {
        $handler = $this->kindOf->getDBHandler($this->connector);
        $handler->update(['id' => $this->id,
            'text' => $this->text,
            'category_id' => $this->category->getId()]);
        foreach ($this->relationMapper as $relation){
            if ($relation['id'] == null) $this->relator->create($relation);
            else $this->relator->update($relation);
        }
    }


}