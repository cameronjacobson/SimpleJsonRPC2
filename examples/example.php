<?php

// This example interfaces with Scalaris, since that's the JSONRPC project I'm working on right now
// A cleaner interface to Scalaris can be found in a separate project I'm calling "Cunchu"

require_once(dirname(__DIR__).'/vendor/autoload.php');

use SimpleJsonRPC2\SimpleJsonRPC2;
use SimpleHttpClient\SimpleHttpClient;


$jsonrpc = new SimpleJsonRPC2(
	new SimpleHttpClient([
		'host'=>'localhost',
		'port'=>8000,
		'contentType'=>'application/json',
		'debug'=>true
	])
);

$start = microtime(true);
try{

	$result = $jsonrpc->request('/api/tx.yaws',array(
		'method'  => 'req_list',
		'params'  => array(array(
			array('write'=>array('key'=>array('type'=>'as_is','value'=>'value'))),
			array('write'=>array('key2'=>array('type'=>'as_is','value'=>'value2'))),
			array('commit'=>'')
		)),
		'id'      => rand(1,100)
	));
	var_dump($result);

	$result = $jsonrpc->request('/api/tx.yaws',array(
		'jsonrpc' => '2.0',
		'method'  => 'req_list',
		'params'  => array(array(
			array('read'=>'key'),
			array('read'=>'key2')
		)),
		'id'      => rand(1,100)
	));

	var_dump($result);
}catch(\Exception $e){
	echo $e->getMessage().PHP_EOL;
}

echo PHP_EOL.'FINISHED IN: '.(microtime(true)-$start).PHP_EOL;

