<?php

namespace Core;

class App
{
    protected $controller = '';
    protected $method = '';
    protected $params = [];

    public function __construct()
    {

        $url = $this->parseUrl();

        if (isset($url[0]) && file_exists('../app/Controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        $this->controller = 'App\\Controllers\\' . $this->controller;

        if (class_exists($this->controller)) {
            $this->controller = new $this->controller;
        } else {
            exit;
        }

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];
    }

    public function run()
    {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
