<?php


namespace quiz;
class App
{
    private string $prefix = '\core\\';
    private string $controller = '\core\UserController';
    private string $method = 'index';

    private function urlExplode(): array
    {
        return explode('/', $_GET['url']);
    }

    public function loadController(): void
    {
        $url = $this->urlExplode();
        $file = '../App/Controller/' . ucfirst($url[0]) . 'Controller.php';

        if (file_exists($file)) {
            require $file;
            $this->controller = ucfirst($url[0]) . 'Controller';
            $controller = new $this->controller;

            if (!empty($url[1])) $this->method = method_exists($controller, $url[1]) ? $url[1] : 'index';
            $data = isset($url[2]) ? [$url[2]] : [];
            call_user_func_array([$controller, $this->method], $data);
        } else $this->pageNotFound();
    }

    private function pageNotFound()
    {
        require '../app/Controller/PageNotFoundController.php';
        $this->controller = $this->prefix . 'PageNotFoundController';

    }

}