<?php 

use \Hcode\Page;
use \Hcode\Model\User;
use \Hcode\PagSeguro\Config;
use \HCode\PagSeguro\Transporter;
use \Hcode\Model\Order;



$app->get('/payment', function(){

	User::verifyLogin(false);

	$order = new Order();

	$order->getFromSession();

	$years = [];

	for ($y = date('Y'); $y < date('Y')+14; $y++)
	{
		array_push($years, $y);
	}

	$page = new page();

	$page-> setTpl("payment", [
		"order"=>$order->getValues(),
		"msgError"=>Order::getError(),
		"years"=>$years,
		"PagSeguro"=>[
			"urlJS"=>Config::getUrlJS(),
			"id"=>Transporter::createSession()
		]
	]);




});

/*
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

*/