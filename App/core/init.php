<?php
require 'App.php';
require 'config.php';
require 'Controller.php';
require 'DataBase.php';
require 'Model.php';
require '../vendor/autoload.php';

spl_autoload_register(function ($class) {
    require $filename = dirname(__FILE__) . '\..\Model\\' . $class . '.php';
});
