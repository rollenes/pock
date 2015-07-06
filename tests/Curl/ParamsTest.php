<?php

namespace Rollenes\Pock\Test\Curl;

use Rollenes\Pock\Curl\Params;

class ParamsTest extends \PHPUnit_Framework_TestCase
{
    public function test_should_reset_params()
    {
        $params = new Params();

        $params->url = 'http://example.com';
        $params->returnTransfer = true;

        Params::reset($params);

        $this->assertNull($params->url);
        $this->assertFalse($params->returnTransfer);
    }

    public function test_should_return_version()
    {
        $version = Params::version();

        $this->assertEquals('Pock#dev-master', $version['version']);
    }
}
