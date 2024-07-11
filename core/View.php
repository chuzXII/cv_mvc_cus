<?php

namespace Core;

class View
{
    protected static $sections = [];
    protected static $sectionStack = [];
    protected static $contentBuffer = '';
    protected static $layout = null;

    public static function render($view, $data = [])
    {
        extract($data);

        // Start output buffering
        ob_start();
        require self::getViewPath($view);
        

        self::$contentBuffer = ob_get_clean();

        // Render the content within the layout if extends is used
        if (self::$layout !== null) {
            ob_start();
            require self::getViewPath(self::$layout);
            echo ob_get_clean();
            exit;
        } else {
            echo self::$contentBuffer;
            exit;
        }
    }

    public static function startSection($section)
    {
        self::$sectionStack[] = $section;
        ob_start();
    }

    public static function stopSection()
    {
        $section = array_pop(self::$sectionStack);
        self::$sections[$section] = ob_get_clean();
    }

    public static function yield($section)
    {
        return self::$sections[$section] ?? '';
    }

    public static function extends($layout)
    {
        self::$layout = $layout;
    }

    public static function include($view, $data = [])
    {
        extract($data);
        require self::getViewPath($view);
    }

    protected static function getViewPath($view)
    {
        // var_dump('../app/Views/' . str_replace('.', '/', $view) . '.php');
        return '../app/Views/' . str_replace('.', '/', $view) . '.php';
    }
}
