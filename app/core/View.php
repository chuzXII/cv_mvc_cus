<?php
namespace App\Core;

use Exception;

class View {
    protected $viewPath;

    public function __construct($viewPath) {
        $this->viewPath = $viewPath;
    }

    public function render($view, $data = []) {
        $viewFile = $this->viewPath . '/' . $view . '.php';

        if (file_exists($viewFile)) {
            extract($data);
            ob_start();
            require $viewFile;
            return ob_get_clean();
        } else {
            throw new Exception("View file '$viewFile' not found.");
        }
    }
}
?>
