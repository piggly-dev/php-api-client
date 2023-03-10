<?php

namespace Piggly\ApiClient\Environments;

use Piggly\ApiClient\Configuration;
use Piggly\ApiClient\Interfaces\ApplicationInterface;
use Piggly\ApiClient\Interfaces\EnvInterface;
use Piggly\ApiClient\Request;

/**
 * Abstract enviroment.
 *
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Environments
 * @version 1.0.11
 * @since 1.0.11
 * @category Environment
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license PGLY
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
abstract class AbstractEnvironment implements EnvInterface
{
	/**
	 * Base URL.
	 * You must replace it with env url.
	 *
	 * @var string
	 * @since 1.0.11
	 */
	protected $_url = '';

	/**
	 * Init enviroment at client configuration.
	 *
	 * @param Configuration $client
	 * @param ApplicationInterface $app
	 * @since 2.1.0
	 * @return void
	 */
	public function init(Configuration $client, $app)
	{
		// Base host for requests
		$client->host($this->_url);
	}

	/**
	 * Prepare request authenticated requests, filling its headers,
	 * access token, setting host, and anything else.
	 * Must return request created.
	 *
	 * @param Configuration $client
	 * @param ApplicationInterface $app
	 * @since 2.1.0
	 * @return Request
	 */
	public function prepare(Configuration $client, $app): Request
	{
		// Must clone to keep original setup
		$_client = $client->clone();

		// Ensure host is as expected
		$_client->host($this->_url);
		$request  = new Request($_client);
		$credentials = $this->token($client, $app);

		$request->headers([
			'Authorization' => \sprintf('%s %s', $credentials->get('token_type'), $credentials->get('access_token')),
		]);

		return $request;
	}
}
