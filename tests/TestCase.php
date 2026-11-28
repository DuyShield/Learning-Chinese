<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
     public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        if (config('database.default') === 'sqlite') {
            DB::connection()->getPdo()->sqliteCreateCollation(
                'utf8mb4_bin',
                fn ($a, $b) => strcmp($a, $b)
            );
        }

        return $app;
    }
}
