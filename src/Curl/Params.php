<?php

namespace Rollenes\Pock\Curl;

class Params
{
    public $url;

    public $returnTransfer = false;

    /**
     * curl_init replacement
     *
     * @param null $url
     * @return Params
     */
    public static function init($url = null)
    {
        $params = new Params();

        $params->url = $url;

        return $params;
    }

    /**
     * curl_setopt replacement
     *
     * @param Params $params
     * @param $option
     *
     * @param $value
     */
    public static function setopt(Params $params, $option, $value)
    {
        if ($option === CURLOPT_RETURNTRANSFER) {
            $params->returnTransfer = $value;
            return;
        }

        if ($option === CURLOPT_URL) {
            $params->url = $value;
            return;
        }
    }

    /**
     * curl_setopt_array replacement
     *
     * @param Params $params
     * @param array $options
     */
    public static function setoptArray(Params $params, array $options)
    {
        foreach ($options as $option => $value)
        {
            self::setopt($params, $option, $value);
        }
    }

    /**
     * curl_close replacement
     *
     * @param Params $params
     */
    public static function close(Params $params)
    {
        //connection closed - no implementaion
    }

    /**
     * curl_reset replacement
     *
     * @param Params $params
     */
    public static function reset(Params $params)
    {
        $params->url = null;
        $params->returnTransfer = false;
    }

    /**
     * curl_version replacement
     *
     * @return array
     */
    public static function version()
    {
        $version = \curl_version();

        $version['version'] = 'Pock#dev-master';

        return $version;
    }
}
