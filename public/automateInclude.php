<?php
spl_autoload_register(function ($class){
    $part = explode('\\', $class);
    $class = $part[1];
    require $_SERVER['DOCUMENT_ROOT'] . '/App/class/' . $class . '.php';
});


