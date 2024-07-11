<?php

namespace Core;

class Router
{
    public static $routes = [];

    public static function get($uri, $controller)
    {
        self::$routes[] = ['GET', $uri, $controller];
    }

    public static function post($uri, $controller)
    {
        self::$routes[] = ['POST', $uri, $controller];
    }

    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, '/cv_mvc_cus') === 0) {
            $uri = substr($uri, strlen('/cv_mvc_cus'));
        }
        // $uri = trim($uri, '/');
        // var_dump($uri);
        $routeFound = false;
        foreach (self::$routes as $route) {
            // var_dump($uri);
            $routeUri = trim($route[1], '/');

            $routeUriPattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $routeUri);
            $routeUriPattern = "@^" . "/" . $routeUriPattern . "$@";

            // var_dump($routeUriPattern);

            if (preg_match($routeUriPattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $controllerData = $route;


                $routeFound = true;
                break;
            }
        }

        if ($routeFound) {
            array_shift($matches); // Remove the full match from the matches array
            list($controller, $method) = explode('@', $route[2]);

            $controller = 'App\Controllers\\' . $controller;
            call_user_func_array([new $controller, $method], $matches);
            return;
        } {
            http_response_code(404);
            echo 'Page not found';
        }
        // die();



    }
}
