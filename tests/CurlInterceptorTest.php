<?php

namespace Rollenes\Pock\Tests;

use Rollenes\Pock\CurlInterceptor;
use Rollenes\Pock\Test\Fixtures\CurlReader;
use Rollenes\Pock\Test\Fixtures\N1\Reader as N1Reader;
use Rollenes\Pock\Test\Fixtures\N2\Reader as N2Reader;

class CurlInterceptorTest extends \PHPUnit_Framework_TestCase
{
    public function test_should_be_intercepted()
    {
        $reader = new CurlReader();

        $interceptor = new CurlInterceptor('Rollenes\Pock\Test\Fixtures');

        $interceptor->intercept();

        $this->assertEquals('intercepted', $reader->get('http://google.com'));
    }

    public function test_should_intercept_in_separate_namespace()
    {
        $reader = new N1Reader();

        $interceptor = new CurlInterceptor('Rollenes\Pock\Test\Fixtures\N1');

        $interceptor->intercept();

        $this->assertEquals('intercepted', $reader->get('http://google.com'));
    }

    public function test_should_intercept_when_function_is_defined_in_namespace()
    {
        $reader = new N2Reader();

        $interceptor = new CurlInterceptor('Rollenes\Pock\Test\Fixtures\N2');
        $interceptor->intercept();

        $this->assertEquals('intercepted', $reader->get('http://google.com'));
    }

    public function test_should_intercept_separate_uri()
    {
        $reader = new CurlReader();

        $interceptor = new CurlInterceptor('Rollenes\Pock\Test\Fixtures');

        $interceptor->intercept([
            'http://google.com' => 'google-intercepted',
            'http://github.com' => 'github-intercepted'
        ]);

        $this->assertEquals('google-intercepted', $reader->get('http://google.com'));
        $this->assertEquals('github-intercepted', $reader->get('http://github.com'));
    }

}
