<?php 

use \Hcode\Page;
use \Hcode\Model\User;
use \GuzzleHttp\Client;
use \Hcode\PagSeguro\Config;

$app->get('/payment/pagseguro', function() {

	 $client = new \GuzzleHttp\Client();
     $response = $client->request('POST', Config::getUrlSessions() . "?" . http_build_query(Config::getAuthentication()), [
		 'verify'=>false
	 ]);
//echo $response->getStatusCode();
 // 200
//echo $response->getHeaderLine('content-type'); 
// 'application/json; charset=utf8'
echo $response->getBody()-> getContents();


});
