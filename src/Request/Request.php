<?php

namespace Httpful\Request;

class Request {

    public function createRequest($method, $url, $body = null, $header = []){

        $method = strtoupper($method);
        $header = array_change_key_case($header, CASE_LOWER);
        $content = '';
        switch($method) {
            case 'OPTIONS':
            case 'GET':
                if(is_array($body)){
                    if(strpos($url, '?') !== false){
                        $url .= '&';
                    } else {
                        $url .= '?';
                    }

                    $url .= urldecode(http_build_query($body));
                }
            break;
            case 'DELETE':
            case 'PUT':
            case 'POST':
                if(is_array($body)) {
                    if(!empty($header['content-type'])) {
                        switch (trim($header['content-type'])) {
                            case 'application/x-www-form-urlencoded' :
                                $body = http_build_query($body);
                                break;
                            case 'application/json' :
                                $body = json_encode($body);
                                break;
                        }
                    } else {
                        $header['content-type'] = 'application/x-www-form-urlencoded';
                        $body = http_build_query($body);
                    }
                } elseif(empty($header['content-type'])){
                    $header['content-type'] = 'application/x-www-form-urlencoded';
                }
            break;
        }

        $option = [
            'http' => [
                'method' => $method
            ]
        ];
        
        if($header) {
            $option['http']['header'] = implode("\r\n", 
                                        array_map(function($value, $key){
                                            return sprintf("%s:, %s", $key, $value);
                                        }, $header, array_keys($header)));
        }
        if ($content) {
            $option['http']['content'] = $content;
        }

        return [$url, $option];
    }
}