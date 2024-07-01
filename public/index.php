<?php
namespace quiz;
require '../App/core/init.php';

session_start();
$_SESSION['UserId'] = 2;

$router = new App();
$router->loadController();


//$conn = new MariaDBConnector();
//
//$loader = new \Twig\Loader\FilesystemLoader('../App/view');
//$twig = new \Twig\Environment($loader);
//$viewname = "quiz/answerQuestion";
////$viewname = "quiz/selectQuestions";
//
////$viewFile = '../App/view/' .  ucfirst($viewname). '.html.twig';
//$viewFile = "../App/view/". $viewname . ".html.twig";
////$viewFile = "../App/view/quiz/selectQuestions.html.twig";
//if (file_exists($viewFile)) {
//
//    echo $twig->render("$viewname.html.twig",['question' => ['id' => 1, 'text'=>'Frage blablblablablbalbl',
//    'answers' => [['id' => 1, 'text' => 'CPU'],
//                ['id' => 2, 'text' => 'RAM'],
//                ['id' => 3, 'text' => 'ROM'],
//                ['id' => 4, 'text' => 'Blub']],
//    'stats' => ['id'=>1, 'timesAsked' => 2, 'timesRight'=> 1]]]);
//

//    echo $twig->render("$viewname.html.twig",['categories' => [
//        ['id'=> 1,'name' => 'PC','number'=> 10],
//        ['id'=> 2,'name' => 'Bla','number'=> 15],
//        ['id'=> 3,'name' => 'suelz','number'=> 17],
//        ['id'=> 4,'name' => 'blub','number'=> 19],
//        ['id'=> 4,'name' => 'blub','number'=> 19],
//        ['id'=> 4,'name' => 'blub','number'=> 19],
//        ['id'=> 4,'name' => 'blub','number'=> 19],
//        ['id'=> 5,'name' => 'PC','number'=> 21]
//        ]]);


//            require $viewFile;
//}
//include "../view/quiz/selectQuestions.html.twig";