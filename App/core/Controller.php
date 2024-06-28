<?php
namespace quiz;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    public function view(string $viewname, array $data): void
    {
        $loader = new FilesystemLoader('../App/View');
        $twig = new Environment($loader);


        $viewFile = '../App/View/' .  ucfirst($viewname). '.html.twig';
        if (file_exists($viewFile)) {
            try {
                echo $twig->render("$viewname.html.twig", $data);
            } catch (LoaderError $e) {
            } catch (RuntimeError $e) {
            } catch (SyntaxError $e) {
            }
//            require $viewFile;
        }
        else require '../App/View/PageNotFoundController.php';
    }

}