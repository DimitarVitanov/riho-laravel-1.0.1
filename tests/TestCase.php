<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $database = $_ENV['DB_DATABASE'] ?? $_SERVER['DB_DATABASE'] ?? getenv('DB_DATABASE') ?: null;
        $cachedConfigPath = dirname(__DIR__) . '/bootstrap/cache/config.php';

        if (is_file($cachedConfigPath)) {
            $cachedConfig = require $cachedConfigPath;
            $connection = $cachedConfig['database']['default'] ?? null;
            $database = $cachedConfig['database']['connections'][$connection]['database'] ?? $database;
        }

        if (!is_string($database) || !str_ends_with($database, '_testing')) {
            throw new \RuntimeException("Tests require a dedicated *_testing database; refusing to use [{$database}]. Run php artisan optimize:clear first.");
        }

        parent::setUp();
    }
}
