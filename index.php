<?php

namespace Httpful;

require_once 'vendor/autoload.php';
use Httpful\Httpclient\APIResponse;
use Httpful\RequestAPI;

function getRequest()
{

    $response = RequestAPI::get('https://jsonplaceholder.typicode.com/');
    APIResponse::dumpResponse($response);
}

function postRequest()
{

    $response = RequestAPI::post('https://postman-echo.com/post', 'Any Thing');
    APIResponse::dumpResponse($response);

}

function postRequestWithJson()
{
    $response = RequestAPI::post(
        'https://postman-echo.com/post',
        ['foo' => 'bar', 'lorem' => 'ipsum'],
        ['content-type' => 'application/json']
    );
    APIResponse::dumpResponse($response);
}

function sendAssessment()
{
    $tokenResponse = RequestAPI::options('https://corednacom/corewebdna.com/assessment-endpoint.php');

    $response = RequestAPI::post(
        'https://corednacom.corewebdna.com/assessment-endpoint.php',
        [
            'name' => 'Hoanh Le Kien',
            'email' => 'lkhoanh@cmcglobal.vn',
            'url' => 'https://github.com/raymondugv/http-client',
        ],
        [
            'Authorization' => 'Bearer ' . $tokenResponse->getBody(),
            'content-type' => 'application/json',
        ]
    );

    APIResponse::dumpResponse($response);
}

//getRequest();
//postRequest();
//postRequestWithJson();
// sendAssessment();
