<?php

namespace Httpful\Httpclient;

use Exception;

class Utils {

    public static function dump($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    public static function dumpResponse($response){
        
        try {
            echo '<h1>Response payload:</h1>';
            self::dump($response);

        } catch (Exception $e){
            self::dump($e);
        }
    }
}