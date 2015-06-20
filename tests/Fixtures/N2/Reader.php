<?php

namespace Rollenes\Pock\Test\Fixtures\N2;

function curl_exec()
{
    return 'curl exec in N2';
}

class Reader
{
    public function get($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }
} 