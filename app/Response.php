<?php

class Response
{
    private $response;
    private $headers;

    public function __construct($response, $headers = [])
    {
        $this->response = $response;
        $this->headers = $headers;
    }

    public function getBody()
    {
        // If the payload is in json, try to decode json.
        if (strpos(strtolower(implode(', ', $this->getHeaders())), 'application/json') !== false) {
            $result = json_decode($this->response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $result;
            } else {
                throw new Exception("Error decoding JSON: " . json_last_error());
            }
        }

        return $this->response;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
