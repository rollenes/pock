<?php

namespace Rollenes\Pock;

class CurlInterceptor
{
    public function intercept($namespace)
    {
        eval(
            'namespace ' . $namespace . ' {
                function curl_exec() {return "intercepted";}
            }
            '
        );
    }
} 