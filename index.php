<?php

namespace Httpful;

require_once 'vendor/autoload.php';
use Httpful\Httpclient\Utils;
use Httpful\RestAPI;

function getRequest()
{

    $response = RestAPI::get('https://jsonplaceholder.typicode.com/');
    Utils::dumpResponse($response);
}

function postRequest()
{

    $response = RestAPI::post('https://postman-echo.com/post', 'Any Thing');
    Utils::dumpResponse($response);

}

function postRequestWithJson()
{
    $response = RestAPI::post(
        'https://postman-echo.com/post',
        ['foo' => 'bar', 'lorem' => 'ipsum'],
        ['content-type' => 'application/json']
    );
    Utils::dumpResponse($response);
}

//getRequest();
//postRequest();
//postRequestWithJson();
