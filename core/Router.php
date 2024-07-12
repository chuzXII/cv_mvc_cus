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
        return new static; // Mengembalikan instance Router untuk chaining
    }

    public static function group($middleware, $callback)
    {
        // Simpan grup middleware saat ini
        $previousGroupMiddleware = self::$groupMiddleware;

        // Tambahkan middleware baru ke grup saat ini
        self::$groupMiddleware = array_merge(self::$groupMiddleware, $middleware);

        // Panggil callback untuk mendefinisikan rute di dalam grup
        call_user_func($callback);

        // Restore grup middleware sebelumnya setelah selesai dengan grup
        self::$groupMiddleware = $previousGroupMiddleware;
    }
    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, '/cv_mvc_cus') === 0) {
            $uri = substr($uri, strlen('/cv_mvc_cus'));
        }
        // $uri = trim($uri, '/');
        // var_dump($uri);
        $method = $_SERVER['REQUEST_METHOD'];
        $middleware = new Middleware();

        // Register middleware
        foreach (self::$middlewareRegistry as $key => $class) {
            $middleware->add($key, $class);
        }
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
            foreach ($route[3] as $key) {
                // echo "Handling middleware: $key<br>";
               // Debug middleware key
                $middleware->handle($key, $_REQUEST);
                if (headers_sent()) {
                    // echo "Headers already sent, can't redirect<br>";
                }
            }
            list($controller, $method) = explode('@', $route[2]);

            $controller = 'App\Controllers\\' . $controller;

            call_user_func_array([new $controller, $method], $matches);
            return;
        } {
            http_response_code(404);
            // echo 'Page not found';
        }
        // die();



    }
}
