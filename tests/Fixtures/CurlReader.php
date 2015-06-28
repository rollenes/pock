<?php

namespace Rollenes\Pock\Test\Fixtures;

class CurlReader
{
    public function get($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}