<?php

namespace App\Models;

class User extends BaseModel
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'User';

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id'
    ];

    /**
     * @param string $password
     */
    public function setPasswordAttribute(string $password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
}
