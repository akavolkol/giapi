<?php

namespace App\Http\Requests;

class User extends Request
{
    /**
     * {@inheritdoc}
     */
    protected $rules = [
        'password' => 'required',
        'email' => 'required|email|unique:User',
        'avatar' => 'image'
    ];
}