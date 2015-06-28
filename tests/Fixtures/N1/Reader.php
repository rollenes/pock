<?php

namespace Rollenes\Pock\Test\Fixtures\N1;

class Reader
{
    public function get($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}
