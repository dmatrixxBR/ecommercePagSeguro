<?php

namespace Hcode\PagSeguro;

use \GuzzleHttp\Client; 
use \Hcode\Model\Order;

class Transporter {

    public static function createSession()
    {

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', Config::getUrlSessions() . "?" . http_build_query(Config::getAuthentication()), [
            'verify'=>false
        ]);
   
        $xml = simplexml_load_string( $response->getBody()-> getContents());

        return((string)$xml->id);

    }

    public static function sendTransaction(Payment $payment)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', Config::getUrlTransaction() . "?" . http_build_query(Config::getAuthentication()), [
            'verify'=>false,
            'headers'=>[
                'Content-Type'=>'application/xml'
            ],
            'body'=>$payment->getDOMDocument()->saveXml()
        ]);
   
        $xml = simplexml_load_string( $response->getBody()-> getContents());

           // var_dump($xml);

        $order = new Order();

        $order->get((int)$xml->reference);

        $order->setPagSeguroTransactionResponse(

           (string)$xml->code,
           (float)$xml->grossAmount,
           (float)$xml->discountAmount,
           (float)$xml->feeAmount,
           (float)$xml->netAmount,
           (float)$xml->extraAmount,
           (float)$xml->paymentLink
         );

         //var_dump('OK');

         return $xml;
            
    }


}