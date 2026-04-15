<?php

namespace Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $compiledViewPath = storage_path('framework/views/testing-'.Str::uuid());

        app(Filesystem::class)->ensureDirectoryExists($compiledViewPath);
        config()->set('view.compiled', $compiledViewPath);
    }
}
