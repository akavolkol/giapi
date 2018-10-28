<?php

namespace App\Services\GitHub;

use Github\Client as BaseClient;
use Illuminate\Contracts\Container\Container;

class Client extends BaseClient
{
    /**
     * Client constructor.
     * @param Container $container
     */
     public function __construct(Container $container)
     {
         parent::__construct();
         $this->authenticate($container->config->get('github.token'), null, BaseClient::AUTH_URL_TOKEN);
     }
}
