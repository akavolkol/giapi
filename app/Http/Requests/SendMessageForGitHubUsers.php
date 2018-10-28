<?php

namespace App\Http\Requests;

class SendMessageForGitHubUsers extends Request
{
    /**
     * {@inheritdoc}
     */
    protected $rules = [
        'users' => 'required|array',
        'message' => 'required'
    ];
}