<?php

namespace Rollenes\Pock\Curl;

use Rollenes\Pock\Curl\Params;

class Interceptor
{
    /**
     * @var string
     */
    private $namespace;

    /**
     * @param string $namespace
     */
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    public function intercept(array $interceptions = [])
    {
        $this->proxyNotExistingNamespaceFunctions([
            'curl_init',
            'curl_setopt',
            'curl_exec',
            'curl_close',
            'curl_setopt_array'
        ]);

        $this->replaceDefinition('curl_init', '\Rollenes\Pock\Curl\Params::init');
        $this->replaceDefinition('curl_setopt', '\Rollenes\Pock\Curl\Params::setopt');
        $this->replaceDefinition('curl_setopt_array', '\Rollenes\Pock\Curl\Params::setoptArray');
        $this->replaceDefinition('curl_close', '\Rollenes\Pock\Curl\Params::close');

        $this->replaceDefinition('curl_exec', function(Params $ch) use ($interceptions) {
            if (isset($interceptions[$ch->url])) {
                return $interceptions[$ch->url];
            }
            return 'No matching interception';
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
