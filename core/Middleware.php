<?php

namespace Core;

class Middleware
{
    private $middleware = [];

    public function add($key, $middlewareClass)
    {
        $this->middleware[$key] = $middlewareClass;
        // echo "Added middleware: $key -> $middlewareClass<br>";
    // Debug middleware addition
    }

    public function handle($key, $request)
    {
        // echo "Handling middleware for key: $key<br>"; // Debug middleware handling

        if (isset($this->middleware[$key])) {
            $middlewareClass = $this->middleware[$key];
            // echo "Executing middleware: $middlewareClass<br>"; // Debug middleware execution

            if (!class_exists($middlewareClass)) {
                // echo "Middleware class $middlewareClass not found<br>"; // Debug class not found
                exit;// Kembalikan false jika middleware tidak bisa dijalankan
            }

            return (new $middlewareClass)->handle($request);
            // Kembalikan nilai handle() method
        } else {
            // echo "No middleware found for key: $key<br>"; // Debug missing middleware
           exit; // Kembalikan true jika tidak ada middleware yang ditemukan
        }
    }
}
