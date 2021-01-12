<?php

namespace Pdmfc\Shorturl\Tests;

use Pdmfc\Shorturl\Providers\ShortUrlProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ShortUrlProvider::class,
        ];
    }
}
