<?php

namespace Rollenes\Pock;

class CurlInterceptor
{
    public function intercept($namespace, $interceptions = [])
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

        if (!function_exists($namespace . '\curl_init')) {
            eval(
            <<<EVAL
namespace $namespace {
    function curl_init() {
        \$reflection = new \ReflectionFunction('\curl_init');

        return \$reflection->invokeArgs(func_get_args());
    }
}
EVAL
            );
        }

        if (!function_exists($namespace . '\curl_setopt')) {
            eval(
            <<<EVAL
namespace $namespace {
    function curl_setopt() {
        \$reflection = new \ReflectionFunction('\curl_setopt');

        return \$reflection->invokeArgs(func_get_args());
    }
}
EVAL
            );
        }

        \Patchwork\replace($namespace . '\curl_init', function($url = null) {
            $ch = new \stdClass();

            if ($url) {
                $ch->{10002} = $url;
            }

            return $ch;
        });

        \Patchwork\replace($namespace . '\curl_setopt', function($ch, $opt, $val) {
            $ch->$opt = $val;
        });

        \Patchwork\replace($namespace . '\curl_exec', function($ch) use ($interceptions) {
            if (isset($interceptions[$ch->{10002}])) {
                return $interceptions[$ch->{10002}];
            }
            return 'intercepted';
        });
    }
} 