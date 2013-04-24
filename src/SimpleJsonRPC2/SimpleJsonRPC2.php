<?php

namespace SimpleJsonRPC2;

use \SimpleHttpClient\SimpleHttpClient;
use \SimpleJsonRPC2\SimpleJsonRPC2Exception;

class SimpleJsonRPC2
{
	const version = '2.0';
	private $transport;
	private $params;
	private $id;
	private $method;

	public function __construct(SimpleHttpClient $http){
		$this->transport = $http;
	}

	public function setParams($params){
		$this->params = $params;
	}

	public function setMethod($method){
		$this->method = (string)$method;
	}

	public function setId($id){
		$this->id = (int)$id;
	}

	public function notification($path, Array $notification){
		$this->send($path, array(
			'jsonrpc'=>self::version,
			'method'=> @$notification['method'] ?: $this->method,
			'params'=> @$notification['params'] ?: $this->params
		));
		return;
	}

	public function request($path, Array $request){
		$response = $this->send($path, array(
			'jsonrpc'=>self::version,
			'method'=> @$request['method'] ?: $this->method,
			'params'=> @$request['params'] ?: $this->params,
			'id'=> @$request['id'] ?: $this->id
		));
		$this->error($response);
		return $response;
	}

	private function send($path, Array $request){
		$response = $this->transport->post($path, json_encode($request));
		$headers = $response['headers'];
		$body = $response['body'];
		return json_decode($body,true);
	}

	private function error(Array $response){
		if(isset($response['error'])){
			throw new SimpleJsonRPC2Exception(json_encode($response));
		}
	}
}
