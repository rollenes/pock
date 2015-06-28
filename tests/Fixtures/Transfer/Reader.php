<?php

namespace Rollenes\Pock\Test\Fixtures\Transfer;

class Reader
{
    public function get($url)
    {
        $ch = curl_init($url);

        curl_exec($ch);
    }
}
