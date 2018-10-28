<?php

return [
    'default' => 'mysql',
    'migrations' => 'migrations',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT', 3306),
            'database' => getenv('DB_DATABASE'),
            'charset' => 'UTF8',
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'engine' => 'InnoDB',
        ]
    ]
];
