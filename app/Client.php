<?php

require_once 'app/Response.php';

class Client
{
    public static function send($method, $url, $body = null, $headers = [])
    {
        [$url, $options] = self::makeRequest($method, $url, $body, $headers);

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            $status_line = implode(',', $http_response_header);
            preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
            $status = $match[1];

            // If the status code not in 2xx or 3xx, throw an exception.
            if (strpos($status, '2') !== 0 && strpos($status, '3') !== 0) {
                throw new Exception("Unexpected response status: {$status} while fetching {$url}\n" . $status_line);
            }
        }

        return new Response($result, $http_response_header);
    }

    private static function makeRequest($method, $url, $body = null, $headers = [])
    {
        $content = '';

        $method = strtoupper($method);
        $headers = array_change_key_case($headers, CASE_LOWER);

        switch ($method) {
            case 'HEAD':
            case 'OPTIONS':
            case 'GET':
                if (is_array($body)) {
                    if (strpos($url, '?') !== false) {
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
                if (is_array($body)) {
                    if (!empty($headers['content-type'])) {
                        switch (trim($headers['content-type'])) {
                            case 'application/x-www-form-urlencoded':
                                $body = http_build_query($body);
                                break;
                            case 'application/json':
                                $body = json_encode($body);
                                break;
                        }
                    } else {
                        $headers['content-type'] = 'application/x-www-form-urlencoded';
                        $body = http_build_query($body);
                    }
                } elseif (empty($headers['content-type'])) {
                    $headers['content-type'] = 'application/x-www-form-urlencoded';
                }

                $content = $body;
                break;
        }

        $options = [
            'http' => [
                'method' => $method,
            ],
        ];

        if ($headers) {
            $options['http']['header'] = implode(
                "\r\n",
                array_map(
                    function ($v, $k) {
                        return sprintf("%s: %s", $k, $v);
                    },
                    $headers,
                    array_keys($headers)
                )
            );
        }

        if ($content) {
            $options['http']['content'] = $content;
        }

        return [$url, $options];
    }
}
