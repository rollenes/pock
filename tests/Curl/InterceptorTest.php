<?php

namespace Rollenes\Pock\Test\Curl;

use Rollenes\Pock\Curl\Interceptor;
use Rollenes\Pock\Test\Fixtures\CurlReader;
use Rollenes\Pock\Test\Fixtures\N1\Reader as N1Reader;
use Rollenes\Pock\Test\Fixtures\N2\Reader as N2Reader;

class InterceptorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider notMatchingProvider
     */
    public function test_should_be_intercepted($reader, $namespace)
    {
        $interceptor = new Interceptor($namespace);

        $interceptor->intercept();

        $this->assertEquals('No matching interception', $reader->get('http://google.com'));
    }

    public function notMatchingProvider()
    {
        return [
            'base interception' => [new CurlReader(), 'Rollenes\Pock\Test\Fixtures'],
            'separate namespace' => [new N1Reader(), 'Rollenes\Pock\Test\Fixtures\N1'],
            'with function defined in namespace' => [new N2Reader(), 'Rollenes\Pock\Test\Fixtures\N2']
        ];
    }

    public function test_should_intercept_separate_uri()
    {
        $reader = new CurlReader();

        $interceptor = new Interceptor('Rollenes\Pock\Test\Fixtures');

        $interceptor->intercept([
            'http://google.com' => 'google-intercepted',
            'http://github.com' => 'github-intercepted'
        ]);

        $this->assertEquals('google-intercepted', $reader->get('http://google.com'));
        $this->assertEquals('github-intercepted', $reader->get('http://github.com'));
    }
}
