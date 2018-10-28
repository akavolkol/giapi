<?php

namespace App\Auth;

use App\Config;
use Firebase\JWT\JWT;
use App\Models\User;
use Illuminate\Container\Container;

class Auth
{
    /**
     * TTL of token in seconds
     */
    const LIFE_TIME = 86400;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Container $container)
    {
        $this->config = $container->config;
    }

    /**
     * @param User $user
     * @return string
     */
    public function generateToken(User $user): string
    {
        $now = (new \DateTime())->getTimestamp();
        $token = array(
            "iss" => $this->config->get('app.host'),
            "aud" => $this->config->get('app.host'),
            "iat" => $now,
            "nbf" => $now,
            "exp" => $now + self::LIFE_TIME,
            'sub' => $user['id']
        );

        return JWT::encode($token, $this->config->get('app.jwtSecret'));
    }

    /**
     * @param string $token
     * @return object
     */
    protected function decodeToken(string $token)
    {
        return JWT::decode($token, $this->config->get('app.jwtSecret'), array('HS256'));
    }

    /**
     * @param string $token
     * @return User
     */
    public function resolve(string $token): ?User
    {
        $user = (new User())->findOrFail($this->decodeToken($token)->sub)->first();
        return $user;
    }
}
