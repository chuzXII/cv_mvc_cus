<?php

namespace Core;

class Router
{
    public static $routes = [];
    private static $groupMiddleware = [];
    private static $middlewareRegistry = [];
    public  static $prefix = '';
    public static function get($uri, $controller)
    {
        self::$routes[] = ['GET', self::$prefix . $uri, $controller, self::$groupMiddleware];
    }

    public static function post($uri, $controller)
    {
        self::$routes[] = ['POST', self::$prefix . $uri, $controller, self::$groupMiddleware];
    }

    public static function middleware($key, $class = null)
    {
        if ($class) {
            self::$middlewareRegistry[$key] = $class;
        }
        return new static;
    }

    public static function group($options, $callback)
    {
        // Simpan grup middleware dan prefiks saat ini
        $previousGroupMiddleware = self::$groupMiddleware;
        $previousPrefix = self::$prefix;

        // Tambahkan middleware baru ke grup saat ini
        if (isset($options['middleware'])) {
            self::$groupMiddleware = array_merge(self::$groupMiddleware, $options['middleware']);
        }

        // Tambahkan prefiks baru ke grup saat ini
        if (isset($options['prefix'])) {
            self::$prefix .= '/' . trim($options['prefix'], '/');
        }

        // Panggil callback untuk mendefinisikan rute di dalam grup
        call_user_func($callback);

        // Restore grup middleware dan prefiks sebelumnya setelah selesai dengan grup
        self::$groupMiddleware = $previousGroupMiddleware;
        self::$prefix = $previousPrefix;
    }

    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $parts = explode('/public', dirname($_SERVER['SCRIPT_NAME']));
        if (strpos($uri, $parts[0]) === 0) {
            $uri = substr($uri, strlen($parts[0]));
        }
        $method = $_SERVER['REQUEST_METHOD'];

        $middleware = new Middleware();

        foreach (self::$middlewareRegistry as $key => $class) {
            $middleware->add($key, $class);
        }

        $routeFound = false;
        $methodNotAllowed = false;
        foreach (self::$routes as $route) {
            $routeUri = trim($route[1], '/');
            $routeUriPattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $routeUri);
            $routeUriPattern = "@^" . "/" . $routeUriPattern . "$@";

            if (preg_match($routeUriPattern, $uri, $matches)) {
                if ($route[0] !== $method) {
                    $methodNotAllowed = true;
                    continue;
                }
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
            if ($route[0] === 'POST' && strpos($uri, '/api') !== 0) {
                // Verify CSRF token for non-API routes
                if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                    http_response_code(403); // Forbidden
                    echo 'CSRF Token Verification Failed';
                    die();
                }
            }

            if (is_array($route[2])) {
                list($controller, $method) = $route[2];
            } else {
                list($controller, $method) = explode('@', $route[2]);
                $controller = strpos($uri, '/api') === 0
                    ? 'App\Controllers\Api\\' . $controller
                    : 'App\Controllers\\' . $controller;
            }
          
            
            if (class_exists($controller)) {
                $controllerInstance = new $controller();

                if ($route[0] === 'POST') {
                    $request = new Request();
                    call_user_func_array([$controllerInstance, $method], [$request, ...$matches]);
                } else {
                    call_user_func_array([$controllerInstance, $method], $matches);
                }
            }
            return;
        }

        if ($methodNotAllowed) {
            http_response_code(405);
            echo '405 Method Not Allowed';
            die();
        } else {
            http_response_code(404);
            echo '404 Page Not Found';
            die();
        }
    }
}
