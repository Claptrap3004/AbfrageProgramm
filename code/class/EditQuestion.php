<?php

namespace quiz;

class EditQuestion extends Question
{
    private CanHandleDB $relator;

    // maps AnswerId($key) to RelationAttributesArray($value)
    private array $relationMapper;

    /**
     * @param int $id
     * @param string $text
     * @param CanConnectDB $connector
     * @param IdText $category
     * @param array $rightAnswers
     * @param array $wrongAnswers
     * @param array $relationMapper
     */
    public function __construct(int $id, string $text, CanConnectDB $connector, IdText $category, array $rightAnswers, array $wrongAnswers, array $relationMapper)
    {
        parent::__construct($id, $text, $connector, $category, $rightAnswers, $wrongAnswers);
        $this->relator = KindOf::RELATION->getDBHandler($connector);
        $this->relationMapper = $relationMapper;
    }

    public function setRightAnswer(IdText $answer): void
    {

    }

    public function setWrongAnswer(IdText $answer): void
    {

    }

    public function setCategory(IdText $answer): void
    {

    }

    public function removeAnswer(IdText $answer): void
    {

    }

    public function delete(EditQuestion $question):void
    {

    }

    /**
     * @throws \Exception
     */
    public function saveQuestion():void
    {
        if ((count($this->rightAnswers) + count($this->wrongAnswers)) >= 4) $this->update();
        else throw new \Exception("Speichern der Frage nicht möglich, da weniger als 4 Antwortmöglichkeiten hinterlegt wurden");
    }

    protected function update(): void
    {
        $handler = $this->kindOf->getDBHandler($this->connector);
        $handler->update(['id' => $this->id,
            'text' => $this->text,
            'category_id' => $this->category->getId()]);
        $this->relator->findById($this->getId());
    }


}