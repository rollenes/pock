<?php

namespace Rollenes\Pock\Tests;

use Rollenes\Pock\CurlInterceptor;
use Rollenes\Pock\Test\Fixtures\CurlReader;
use Rollenes\Pock\Test\Fixtures\N1\Reader;

class CurlInterceptorTest extends \PHPUnit_Framework_TestCase
{
    public function test_should_be_intercepted()
    {
        $reader = new CurlReader();

        $interceptor = new CurlInterceptor();

        $interceptor->intercept('Rollenes\Pock\Test\Fixtures');

        $this->assertEquals('intercepted', $reader->get('http://google.com'));
    }

    public function test_should_intercept_in_separate_namespace()
    {
        $reader = new Reader();

        $interceptor = new CurlInterceptor();

        $interceptor->intercept('Rollenes\Pock\Test\Fixtures\N1');

        $this->assertEquals('intercepted', $reader->get('http://google.com'));
    }



}