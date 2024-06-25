<?php
spl_autoload_register(function ($class){
    $part = explode('\\', $class);
    $class = $part[1];
    require '../App/class/' . $class . '.php';
});

