<?php

namespace App\Helpers;

class FunctionHelper {

    public static function curlCustom($url = '', $headers = array(), $method = 'GET', $fields = array() , $is_document = 0)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => ''
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $err;
        } else {
            return $response;
        }
    }

    public static function rapidApiFootball($uri = '', $method = 'GET' )
    {
        $host    = env('RAPIDAPIFOOTBALL');
        $key     = env('RAPIDAPIKEY');
        $url     = sprintf('https://%s/%s', $host, $uri);
        $headers = [sprintf('X-RapidAPI-Host:%s', $host), sprintf('X-RapidAPI-Key:%s', $key)];
        $output  = FunctionHelper::curlCustom($url, $headers, $method);
        $output  = json_decode($output, true);
        return $output;
    }
}
