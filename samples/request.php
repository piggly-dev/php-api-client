<?php

use Piggly\ApiClient\Configuration;
use Piggly\ApiClient\Request;

require_once( '../vendor/autoload.php' );

$config = new Configuration();
$config->host('https://jsonplaceholder.typicode.com/');
	
$request = new Request($config);
list($body, $code, $headers) = $request->get('/posts/{id}')->params(['id'=>1])->call();
die();