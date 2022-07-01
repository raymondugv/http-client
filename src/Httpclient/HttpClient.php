<?php

namespace Httpful\Httpclient;

use Httpful\Response\Response;
use Httpful\Request\Request;
use Httpful\Exeption\ResponseExeption;

class HttpClient {

    public static function send($method, $url, $body = null, $header = []){

        $request = new Request;
        [$url, $option] = $request->createRequest($method, $url, $body, $header);

        $context = stream_context_create($option);
        $result = file_get_contents($url, false, $context);

        if ($result == false){
            $status_line = implode(',', $http_response_header);
            preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
            $status = $match[1];

            if(strpos($status, '2') !== 0 && strpos($status, '3') !== 0){
                throw new ResponseExeption("Unexpected response status: {$status} while fetching {$url}\n" .$status_line);
            }
        }

        return new Response($result, $http_response_header);
    }
}