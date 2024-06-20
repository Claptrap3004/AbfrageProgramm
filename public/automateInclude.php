<?php
spl_autoload_register(function ($class): void{
    include '../code/class/' . $class . '.php';
});

