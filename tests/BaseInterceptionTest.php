<?php

namespace Rollenes\Pock\Tests;

function sizeof($array) {
    return 0;
}

class BaseInterceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testSizeOfInterceptor()
    {
        $this->assertEquals(0, sizeof([]));
        $this->assertEquals(0, sizeof([1]));
        $this->assertEquals(0, sizeof(timezone_abbreviations_list()));

        $this->assertEquals(1, \sizeof([1]));
    }
}
