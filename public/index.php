<?php
require '../vendor/autoload.php';
require 'automate.php';
//include '../core\App/class/MariaDBConnector.php';
use quiz\MariaDBConnector;

$conn = new MariaDBConnector();

$loader = new \Twig\Loader\FilesystemLoader('../App/view');
$twig = new \Twig\Environment($loader);
$viewname = "quiz/selectQuestions";

$viewFile = '../App/view/' .  ucfirst($viewname). '.html.twig';
//$viewFile = "../view/selectQuestions.html.twig";
if (file_exists($viewFile)) {
    echo $twig->render("$viewname.html.twig",['categories' => [
        ['id'=> 1,'name' => 'PC','number'=> 10],
        ['id'=> 2,'name' => 'Bla','number'=> 15],
        ['id'=> 3,'name' => 'suelz','number'=> 17],
        ['id'=> 4,'name' => 'blub','number'=> 19],
        ['id'=> 4,'name' => 'blub','number'=> 19],
        ['id'=> 4,'name' => 'blub','number'=> 19],
        ['id'=> 4,'name' => 'blub','number'=> 19],
        ['id'=> 5,'name' => 'PC','number'=> 21]
        ]]);
//            require $viewFile;
}
//include "../view/quiz/selectQuestions.html.twig";