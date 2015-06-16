<?php

namespace Rollenes\Pock\Tests;

function file_get_contents($filename) {
    return 'this is not github';
}

function fopen($file, $mode) {
    return 'fopen';
}

function fread() {
    return func_get_args();
}

class BaseInterceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testFileGetContentsInterceptor()
    {
        $content = file_get_contents('http://github.com');

        $this->assertEquals('this is not github', $content);
    }

    public function testFreadInterceptor()
    {
        $f = fopen('test', "r");

        $content = fread($f, 1024);

        $this->assertEquals(['fopen', '1024'], $content);
    }
}
