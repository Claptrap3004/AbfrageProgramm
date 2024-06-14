<?php
namespace quiz;
// instantiation of this class works for answers and categories, most other classes can inherit this as base.
// kindOfText defines to which type in the DB the class refers. The Enum deals as kind of controller for choosing the
// correct implementation of CanHandleDB Interface

class IdText
{
    private int $id;
    private string $identifier;
    private KindOfIdText $kindOf;

    /**
     * @param int $id
     * @param string $identifier
     * @param KindOfIdText $kindOf
     */
    public function __construct(int $id, string $identifier, KindOfIdText $kindOf)
    {
        $this->id = $id;
        $this->identifier = $identifier;
        $this->kindOf = $kindOf;
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getKindOf(): KindOfIdText
    {
        return $this->kindOf;
    }


}