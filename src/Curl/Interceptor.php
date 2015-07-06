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
            'curl_setopt_array',
            'curl_reset',
            'curl_version',

            'curl_multi_init',
            'curl_multi_remove_handle',
            'curl_multi_add_handle',
            'curl_multi_info_read',
            'curl_multi_select',
            'curl_multi_exec',
            'curl_multi_close'
        ]);

        $this->replaceDefinition('curl_init', '\Rollenes\Pock\Curl\Params::init');
        $this->replaceDefinition('curl_setopt', '\Rollenes\Pock\Curl\Params::setopt');
        $this->replaceDefinition('curl_setopt_array', '\Rollenes\Pock\Curl\Params::setoptArray');
        $this->replaceDefinition('curl_close', '\Rollenes\Pock\Curl\Params::close');
        $this->replaceDefinition('curl_reset', '\Rollenes\Pock\Curl\Params::reset');
        $this->replaceDefinition('curl_version', '\Rollenes\Pock\Curl\Params::version');


        $this->replaceDefinition('curl_multi_init', function(){});
        $this->replaceDefinition('curl_multi_remove_handle', function(){});
        $this->replaceDefinition('curl_multi_add_handle', function(){});
        $this->replaceDefinition('curl_multi_info_read', function(){return ['handle' => 0, 'result' => 0];});
        $this->replaceDefinition('curl_multi_select', function(){return 1;});
        $this->replaceDefinition('curl_multi_exec', function(){return 'No matching interception';});
        $this->replaceDefinition('curl_multi_close', function(){});

        $this->replaceDefinition('curl_exec', function(Params $ch) use ($interceptions) {

            $result = 'No matching interception';

            if (isset($interceptions[$ch->url])) {
                $result = $interceptions[$ch->url];
            }

            if ($ch->returnTransfer) {
                return $result;
            } else {
                echo $result;
            }
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
