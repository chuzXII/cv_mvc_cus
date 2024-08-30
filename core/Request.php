<?php

namespace Core;

class Request
{
    protected $data;

    public function __construct()
    {
        // Menggabungkan data dari $_REQUEST dan JSON input
        $this->data = $_REQUEST;
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            $this->data = array_merge($this->data, $input);
        }
    }

    public function input($key = null)
    {
        $default = null;
        return $this->data[$key] ?? $default;
    }

    public function file($key = null)
    {
        if ($key === null) {
            return $_FILES;
        }
        return $_FILES[$key] ?? null;
    }

    public function all()
    {
        return array_merge($this->data, $_FILES);
    }

    public function validate($rules, $messages = [])
    {
        $validator = new Validator($this->data, $rules, $messages);
        $validator->validate();

        return $validator;
    }
}
