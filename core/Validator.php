<?php

namespace Core;

class Validator
{
    protected $data;
    protected $rules;
    protected $messages;
    protected $errors = [];

    public function __construct($data, $rules, $messages = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->messages = $messages;
    }

    public function validate()
    {
        foreach ($this->rules as $field => $rules) {
            $rules = explode('|', $rules);

            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }

        return $this;
    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    protected function applyRule($field, $rule)
    {
        if ($rule === 'required' && empty($this->data[$field])) {
            $this->addError($field, 'required');
        }

        if (strpos($rule, 'unique:') === 0) {
            $params = explode(':', $rule);
            $table = $params[1];
            $value = $this->data[$field];

            // Implementasi validasi unique harus disesuaikan dengan struktur database Anda
            // Misalnya, cek keberadaan data di dalam database
            // Contoh sederhana:
            if ($table === 'posts' && $value === 'existing_value') {
                $this->addError($field, 'unique');
            }
        }

        if ($rule === 'confirmed') {
            $confirmationField = $field . '_confirmation';
            if (!isset($this->data[$confirmationField]) || $this->data[$field] !== $this->data[$confirmationField]) {
                $this->addError($field, 'confirmed');
            }
        }

        // Implementasi aturan validasi lainnya seperti 'max:255', 'email', dll.
        // Anda bisa menambahkan fungsi validasi sesuai kebutuhan.
    }

    protected function addError($field, $rule)
    {
        $message = $this->messages["$field.$rule"] ?? $this->defaultMessage($field, $rule);
        $this->errors[$field][] = $message;
    }

    protected function defaultMessage($field, $rule)
    {
        $messages = [
            'required' => "The $field field is required.",
            'unique' => "The $field must be unique.",
            'email' => "The $field must be a valid email address.",
            'max' => "The $field may not be greater than :max characters.",
            'confirmed' => "The $field confirmation does not match.",
        ];

        return $messages[$rule] ?? "The $field field has an error.";
    }
}
