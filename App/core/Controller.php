<?php
namespace quiz;

abstract class Controller
{
    public function view(string $viewname, array $data)
    {
        $loader = new \Twig\Loader\FilesystemLoader('../App/View');
        $twig = new \Twig\Environment($loader);


        $viewFile = '../App/View/' .  ucfirst($viewname). '.html.twig';
        if (file_exists($viewFile)) {
            echo $twig->render("$viewname.html.twig",$data);
//            require $viewFile;
        }
        else require '../App/View/PageNotFoundController.php';
    }

}