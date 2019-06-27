<?php

namespace Hcode\PagSeguro;

use \GuzzleHttp\Client; 

class Transporter {

    public static function createSession()
    {

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', Config::getUrlSessions() . "?" . http_build_query(Config::getAuthentication()), [
            'verify'=>false
        ]);
   
        $xml = simplexml_load_string( $response->getBody()-> getContents());

        var_dump((string)$xml->id);

    }


}