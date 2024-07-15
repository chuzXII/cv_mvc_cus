<?php

namespace Core;

class Router
{
    public static $routes = [];
    private static $groupMiddleware = [];
    private static $middlewareRegistry = [];

    public static function get($uri, $controller)
    {
        self::$routes[] = ['GET', $uri, $controller, self::$groupMiddleware];
    }

    public static function post($uri, $controller)
    {
        self::$routes[] = ['POST', $uri, $controller, self::$groupMiddleware];
    }

    public static function middleware($key, $class = null)
    {
        if ($class) {
            self::$middlewareRegistry[$key] = $class;
        }
        return new static;
    }

    public static function group($middleware, $callback)
    {
        $previousGroupMiddleware = self::$groupMiddleware;
        self::$groupMiddleware = array_merge(self::$groupMiddleware, $middleware);
        call_user_func($callback);
        self::$groupMiddleware = $previousGroupMiddleware;
    }

    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $parts = explode('/public', dirname($_SERVER['SCRIPT_NAME']));
        if (strpos($uri, $parts[0]) === 0) {
            $uri = substr($uri, strlen($parts[0]));
        }
        // var_dump($_SERVER['REQUEST_URI'],$parts);
        // // die();
        $method = $_SERVER['REQUEST_METHOD'];
        $middleware = new Middleware();

        foreach (self::$middlewareRegistry as $key => $class) {
            $middleware->add($key, $class);
        }

        $routeFound = false;
        foreach (self::$routes as $route) {
            $routeUri = trim($route[1], '/');
            $routeUriPattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $routeUri);
            $routeUriPattern = "@^" . "/" . $routeUriPattern . "$@";

            if (preg_match($routeUriPattern, $uri, $matches)) {
                array_shift($matches);
                $controllerData = $route;
                $routeFound = true;
                break;
            }
        }

        if ($routeFound) {
            foreach ($route[3] as $key) {
                $middleware->handle($key, $_REQUEST);
                if (headers_sent()) {
                    return;
                }
            }
            if ($route[0] === 'POST') {
                // Verify CSRF token
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    http_response_code(403); // Forbidden
                    echo 'CSRF Token Verification Failed';
                    return;
                }
            }

            list($controller, $method) = explode('@', $route[2]);
            $controller = 'App\Controllers\\' . $controller;

            if (class_exists($controller)) {
                // if ($route[0] === 'POST') {
                //     if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
                //         $url = "https://";
                //     }
                //     else{
                //         $url = "http://";}
                //     // Check if the request is from a form submission
                //     if (!isset($_SERVER['HTTP_REFERER']) || parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) !== $_SERVER['REQUEST_URI']) {
                //         http_response_code(405);
                //         echo 'Method Not Allowed';
                //         // $desiredUrl = getDesiredUrlForLoginForm();
                //         $refererPath = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
                //         $requestUriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                //         if (strpos($requestUriPath, $refererPath) !== 0) {
                //             http_response_code(405);
                //             echo 's';
                //             return;
                //         }
                //         var_dump(strpos($requestUriPath, $refererPath));
                //         return;
                //     }
                // }
                $controllerInstance = new $controller();


                if ($route[0] === 'POST') {
                    $request = new Request();
                    call_user_func_array([$controllerInstance, $method], [$request, ...$matches]);
                } else {
                    call_user_func_array([$controllerInstance, $method], $matches);
                }
            }
        }

        http_response_code(404);
        echo 'Page not found';
    }
}
