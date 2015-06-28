<?php

namespace Rollenes\Pock\Test\Fixtures;

class CurlReader
{
    public function get($url)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
        ]);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}