<?php

namespace Rollenes\Pock;

class CurlInterceptor
{
    private $namespace;

    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    public function intercept($interceptions = [])
    {
        $this->proxyNotExistingNamespaceFunctions(['curl_init', 'curl_setopt', 'curl_exec']);

        $this->replaceDefinition('curl_init', function($url = null) {
            $ch = new \stdClass();

            if ($url) {
                $ch->{10002} = $url;
            }

            return $ch;
        });

        $this->replaceDefinition('curl_setopt', function($ch, $opt, $val) {
            $ch->$opt = $val;
        });

        $this->replaceDefinition('curl_exec', function($ch) use ($interceptions) {
            if (isset($interceptions[$ch->{10002}])) {
                return $interceptions[$ch->{10002}];
            }
            return 'intercepted';
        });
    }

    private function proxyNotExistingNamespaceFunctions(array $toProxy)
    {
        foreach($toProxy as $function) {
            if (!function_exists($this->namespace . '\\' . $function)) {
                $this->createNamespaceProxyFunction($this->namespace, $function);
            }
        }
    }

    private function replaceDefinition($function, $replacement)
    {
        \Patchwork\replace($this->namespace . '\\' . $function, $replacement);
    }

    private function createNamespaceProxyFunction($namespace, $functionName)
    {
        eval(<<<EVAL
namespace $namespace {
    function $functionName() {
        \$reflection = new \ReflectionFunction('\$functionName');

        return \$reflection->invokeArgs(func_get_args());
    }
}
EVAL
        );
    }
}
