<?php

namespace Rollenes\Pock\Curl;

/**
 * Spike implementation TODO rewrite it!
 */
class Params
{
    public $url;

    public static function init($url = null)
    {
        $params = new Params();

        $params->url = $url;

        return $params;
    }

    public static function setopt(Params $params, $option, $value)
    {
        $constants = (new \ReflectionExtension('curl'))->getConstants();

        $key = array_search($option, $constants, true);

        if ($key and self::isCurlOpt($key)) {
            $param = strtolower(substr($key, 8));

            $params->$param = $value;
        } else {
            throw new \RuntimeException('Invalid curlopt: ' . $option);
        }
    }

    public static function setoptArray(Params $params, array $options)
    {
        foreach ($options as $option => $value)
        {
            self::setopt($params, $option, $value);
        }
    }

    /**
     * curl_close replacement
     * @param Params $params
     */
    public static function close(Params $params)
    {
        //connection closed - no implementaion
    }

    private static function isCurlOpt($key)
    {
        return strpos($key, 'CURLOPT_') === 0;
    }
}
