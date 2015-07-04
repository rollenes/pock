<?php

namespace Rollenes\Pock\Test\Fixtures;

use GuzzleHttp\Client;

class GuzzleReader
{
    public function get($url)
    {
        $client = new Client([
            'url' => $url,
            'timeout'  => 1.0,
        ]);

        $response = $client->get('')->send();

        return $response->getBody(true);
    }
} 