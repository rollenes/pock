<?php

namespace Rollenes\Pock\Tests;

if (!function_exists('Rollenes\Pock\Tests\file_get_contents')) {
    function file_get_contents()
    {
        $reflection = new \ReflectionFunction('\file_get_contents');

        return $reflection->invokeArgs(func_get_args());
    }
}

if (!function_exists('Rollenes\Pock\Tests\fopen')) {
    function fopen()
    {
        $reflection = new \ReflectionFunction('\fopen');

        return $reflection->invokeArgs(func_get_args());
    }
}

if (!function_exists('Rollenes\Pock\Tests\fread')) {
    function fread()
    {
        $reflection = new \ReflectionFunction('\fread');

        return $reflection->invokeArgs(func_get_args());
    }
}

class PatchworkInterceptionTest extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        \Patchwork\undoAll();
    }

    public function testFileGetContentsInterception()
    {
        \Patchwork\replace('\Rollenes\Pock\Tests\file_get_contents', function(){
            return 'patchwork_file_get_contents';
        });

        $content = file_get_contents('mama');

        $this->assertEquals('patchwork_file_get_contents', $content);
    }

    public function testFreadInterceptor()
    {
        \Patchwork\replace('\Rollenes\Pock\Tests\fopen', function($filename){
            return $filename . '.patchwork';
        });

        \Patchwork\replace('\Rollenes\Pock\Tests\fread', function(){
            return func_get_args();
        });

        $f = fopen('test', "r");

        $content = fread($f, 1024);

        $this->assertEquals(['test.patchwork', '1024'], $content);
    }
}
