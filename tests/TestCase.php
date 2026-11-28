<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    $app = require __DIR__.'/../bootstrap/app.php';

    $app->make(Kernel::class)->bootstrap();

    // Register MySQL collation for SQLite testing
    if (config('database.default') === 'sqlite') {
        $pdo = DB::connection()->getPdo();

        $pdo->sqliteCreateCollation(
            'utf8mb4_bin',
            fn ($a, $b) => strcmp($a, $b)
        );
    }

    return $app;
}
