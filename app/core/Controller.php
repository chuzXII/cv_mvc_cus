<?php
namespace App\Core;
class Controller
{
    protected $view;
    protected function redirect($route)
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $full_host = $_SERVER['HTTP_HOST'];
        $url_parts = explode(':', $full_host);
        $hostname = $url_parts[0];
        $port = isset($url_parts[1]) ? ':' . $url_parts[1] : '';

        if (isset($url_parts[1])) {
            $redirect_url = "$protocol$full_host$route";
        } else {
            $redirect_url = "$protocol$hostname$route";
        }

        header("Location: $redirect_url");
        exit;
    }
}
