<?php

namespace quiz;


class UserDBHandler extends IdTextDBHandler
{

    protected function validateArgsCreate(array $args): bool
    {
        return array_key_exists('username', $args) &&
            array_key_exists('email', $args) &&
            array_key_exists('password', $args);
    }
}