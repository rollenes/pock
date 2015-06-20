<?php

namespace Rollenes\Pock\Test\Fixtures;

class CurlReader
{
    public function get($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        return curl_exec($ch);
    }
}