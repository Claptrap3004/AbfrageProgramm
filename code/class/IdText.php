<?php
namespace quiz;
// instantiation of this class works for answers and categories, most other classes can inherit this as base.
// kindOfText defines to which type in the DB the class refers. The Enum deals as kind of controller for choosing the
// correct implementation of CanHandleDB Interface

class IdText
{
    private int $id;
    private string $text;
    private KindOf $kindOf;
    private CanConnectDB $connector;

    /**
     * @param int $id
     * @param string $text
     * @param KindOf $kindOf
     * @param CanConnectDB $connector
     */
    public function __construct(int $id, string $text, KindOf $kindOf, CanConnectDB $connector)
    {
        $this->id = $id;
        $this->text = $text;
        $this->kindOf = $kindOf;
        $this->connector = $connector;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
        $this->update();
    }

    public function getKindOf(): KindOf
    {
        return $this->kindOf;
    }

    public function equals(IdText $idText): bool
    {
        return ($this->id === $idText->id & $this->text === $idText->text);
    }
    private function update(): void
    {
        $handler = $this->kindOf->getDBHandler($this->connector);
        $handler->update(['id' => $this->id, 'text' => $this->text]);
    }

}