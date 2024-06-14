<?php

namespace quiz;

interface CanHandleDB
{
    public function create(array $args): int;

    public function findById(int $id): array;

    public function findAll(): array;

    public function update(array $args): void;
    public function deleteAtId(int $id): void;

}