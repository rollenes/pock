<?php

namespace Rollenes\Pock;

class CurlInterceptor
{
    public function intercept($namespace)
    {
        if (!function_exists($namespace . '\curl_exec')) {
            eval(
                <<<EVAL
namespace $namespace {
    function curl_exec() {
        \$reflection = new \ReflectionFunction('\curl_exec');

        return \$reflection->invokeArgs(func_get_args());
    }
}
EVAL
            );
        }

        \Patchwork\replace($namespace . '\curl_exec', function(){
            return 'intercepted';
        });
    }
} 