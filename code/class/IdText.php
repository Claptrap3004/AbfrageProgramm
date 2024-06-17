<?php
namespace quiz;
// instantiation of this class works for answers and categories, most other classes can inherit this as base.
// kindOfText defines to which type in the DB the class refers. The Enum deals as kind of controller for choosing the
// correct implementation of CanHandleDB Interface

class IdText
{
    private int $id;
    private string $text;
    private KindOfIdText $kindOf;

    /**
     * @param int $id
     * @param string $text
     * @param KindOfIdText $kindOf
     */
    public function __construct(int $id, string $text, KindOfIdText $kindOf)
    {
        $this->id = $id;
        $this->text = $text;
        $this->kindOf = $kindOf;
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
    }

    public function getKindOf(): KindOfIdText
    {
        return $this->kindOf;
    }

    public function equals(IdText $idText): bool
    {
        return ($this->id === $idText->id & $this->text === $idText->text);
    }

}