<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $app;

    public function app()
    {
        if (is_null($this->app)) {
            $this->app = require __DIR__ . '/../app/app.php';
        }
        return $this->app;
    }
}
