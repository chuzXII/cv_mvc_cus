<?php

namespace Core;

class Request
{
    protected $data;

    public function __construct()
    {
        $this->data = $_REQUEST; // Menggunakan $_REQUEST untuk mendapatkan data dari POST atau GET
    }

    public function input($key = null)
    {
        // if ($key === null) {
        //     return $this->data;
        // }
        $default = null;
        return $this->data[$key] ?? $default;
    }

    public function all()
    {
        return $this->data;
    }

    public function validate($rules, $messages = [])
    {
        $validator = new Validator($this->data, $rules, $messages);
        $validator->validate();

        return $validator;
    }
}