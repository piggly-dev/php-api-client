<?php

namespace Piggly\ApiClient\Interfaces;

use Piggly\ApiClient\Configuration;
use Piggly\ApiClient\Request;

/**
 * Interface for token authentication according with environment.
 *
 * @since 1.0.9
 * @category Interfaces
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
interface EnvInterface
{
	/**
	 * Init enviroment at client configuration.
	 *
	 * @param Configuration $client
	 * @param ApplicationInterface $app
	 * @since 1.0.9
	 * @return void
	 */
	public function init(Configuration $client, ApplicationInterface $app);

	/**
	 * Do an OAuth connection to get the access token.
	 * Must fill application model with credential returned
	 * and return the credential model created.
	 *
	 * @param Configuration $client
	 * @param ApplicationInterface $app
	 * @since 1.0.9
	 * @return CredentialInterface
	 */
	public function token(Configuration $client, ApplicationInterface $app): CredentialInterface;

	/**
	 * Prepare request authenticated requests, filling its headers,
	 * access token, setting host, and anything else.
	 * Must return request created.
	 *
	 * @param Configuration $client
	 * @param ApplicationInterface $app
	 * @since 1.0.9
	 * @return Request
	 */
	public function prepare(Configuration $client, ApplicationInterface $app): Request;
}
