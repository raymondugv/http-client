<?php

/**
 * Class Utils
 */
class Utils
{

    public static function dumpPretty($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public static function dumpResponse($response)
    {
        try {
            echo '<h1>Response headers:</h1>';
            self::dumpPretty($response->getHeaders());
            echo '<h1>Response payload:</h1>';
            self::dumpPretty($response->getBody());
        } catch (Exception $e) {
            self::dumpPretty($e);
        }
    }
}
