<?php


namespace quiz;

class User extends Model
{
    private int $id;
    private string $username;
    private string $email;
    private string $pwhash;


    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->pwhash = password_hash($password, PASSWORD_BCRYPT);
    }



}