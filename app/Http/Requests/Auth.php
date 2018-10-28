<?php

namespace App\Http\Requests;

class Auth extends Request
{
    /**
     * {@inheritdoc}
     */
    protected $rules = [
        'password' => 'required',
        'email' => 'required|email'
    ];
}