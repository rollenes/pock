<?php

namespace Rollenes\Pock\Test\Fixtures;

class CurlReader
{
    private $ch;

    public function __construct()
    {
        $this->ch = curl_init();
    }

    public function get($url)
    {
        curl_setopt_array($this->ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
        ]);

        $result = curl_exec($this->ch);

        curl_reset($this->ch);
        curl_close($this->ch);

        return $result;
    }
}
