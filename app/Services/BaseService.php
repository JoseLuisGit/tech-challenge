<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

class BaseService
{
    protected MessageBag $errors;

    public function __construct()
    {
        $this->errors = new MessageBag();
    }

    public function hasErrors()
    {
        return $this->errors->any();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function mergeErrors($errors)
    {
        $this->errors->merge($errors);
    }

}