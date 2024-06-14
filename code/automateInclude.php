<?php
spl_autoload_register(function ($class): void{
    include 'class/' . $class . '.php';
});

