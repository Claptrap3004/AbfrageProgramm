<?php
// Interface to provide different implementations for all the different tables respectively classes that implement
// CRUD functionality in the DB
// parameters are defined quite generally, validation of sent data is done in the implementation of this interface

namespace quiz;

interface CanHandleDB
{
    public function create(array $args): int;

    public function findById(int $id): array;

    public function findAll(array $filters = []): array;

    public function update(array $args): bool;
    public function deleteAtId(int $id): bool;

}