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

        if ($rule === 'file' && isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            $this->addError($field, 'file');
        }

        if (strpos($rule, 'mimes:') === 0 && isset($_FILES[$field])) {
     
            if (!empty($_FILES[$field]['tmp_name'])) {
                $mime = mime_content_type($_FILES[$field]['tmp_name']);
                $allowedTypes = explode(',', str_replace('mimes:', '', $rule));
                $fileType = mime_content_type($_FILES[$field]['tmp_name']);

                if (!in_array($fileType, $allowedTypes)) {
                    $this->addError($field, 'mimes');
                }
            } else {
                // $this->addError($field, 'file');
            }
        }

        if (strpos($rule, 'max:') === 0 && isset($_FILES[$field])) {
            $maxSize = (int) str_replace('max:', '', $rule) * 1024;
            $fileSize = $_FILES[$field]['size'];

            if ($fileSize > $maxSize) {
                $this->addError($field, 'max');
            }
        }

        if ($rule === 'confirmed') {
            $confirmationField = $field . '_confirmation';
            if ($this->data[$field] !== $this->data[$confirmationField]) {
                $this->addError($field, 'confirmed');
            }
        }
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
            'file' => "The $field field is not file or not found",
            'mimes' => "The $field field must be a JPEG or PNG image.",



        ];

        return $messages[$rule] ?? "The $field field has an error.";
    }
}
