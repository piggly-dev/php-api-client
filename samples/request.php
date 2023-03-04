<?php

use Piggly\ApiClient\Configuration;
use Piggly\ApiClient\Exceptions\ApiRequestException;
use Piggly\ApiClient\Exceptions\ApiResponseException;
use Piggly\ApiClient\Request;

require_once('../vendor/autoload.php');

$config = new Configuration();
$config->host('https://jsonplaceholder.typicode.com/');

$request = new Request($config);

try {
	$response = $request->get('/posts/{id}')->params(['id'=>1])->call();

	$body = $response->getBody();
	$status = $response->getStatus();
	$headers = $response->getHeaders();
} catch (ApiRequestException $e) {
	$message = $e->getMessage();
} catch (ApiResponseException $e) {
	$response = $e->getResponse();
}
die();
