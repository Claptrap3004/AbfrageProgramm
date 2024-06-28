<?php


namespace quiz;
class App
{
    private string $prefix = '\quiz\\';
    private string $controller = '\quiz\UserController';
    private string $method = 'index';

    private function urlExplode(): array
    {
        $url = ltrim($_GET['url'], '/');
        return explode('/', $url);
    }

    public function loadController(): void
    {
        $url = $this->urlExplode();
        $file = '../App/Controller/' . ucfirst($url[0]) . 'Controller.php';
        if (file_exists($file)) {
            require $file;
            $this->controller = $this->prefix . ucfirst($url[0]) . 'Controller';
            $controller = new $this->controller;

            if (!empty($url[1])) $this->method = method_exists($controller, $url[1]) ? $url[1] : 'index';
            $data = isset($url[2]) ? [$url[2]] : [];
            call_user_func_array([$controller, $this->method], $data);
        } else header("refresh:0.01;url='https://abfrageprogramm.ddev.site:8443/QuizQuestion'");;
    }


}