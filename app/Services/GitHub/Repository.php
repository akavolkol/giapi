<?php

namespace App\Services\GitHub;

class Repository
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Repository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $userName
     * @return mixed
     */
    public function userDetails(string $userName)
    {
        return $this->client->api('users')->show($userName);
    }

    /**
     * @param array $userNames
     * @return \Generator
     */
    public function usersDetails(array $userNames): \Generator
    {
        foreach ($userNames as $userName) {
            yield $this->userDetails($userName);
        }
    }
}