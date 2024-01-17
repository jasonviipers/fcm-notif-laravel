<?php

namespace Viipers\FcmNotifLaravel\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    public function tearDown()
    {
        parent::tearDown();
        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }
        Mockery::close();
    }
}

if (! function_exists('config')) {
    function config($key = null)
    {
        return 1;
    }
}