<?php

namespace Piggly\ApiClient\Exceptions;

use Exception;
use Monolog\Logger;
use Piggly\ApiClient\Response;

/**
 * An Api Exception which makes link to server response object,
 * HTTP headers and body.
 *
 * @since 2.0.0
 * @category Class
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Client
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class ApiResponseException extends Exception
{
	/**
	 * HTTP response.
	 * @var Response
	 * @since 2.0.0
	 */
	protected $_response;

	/**
	 * Constructor to exception.
	 *
	 * @param string $message
	 * @param integer $code
	 * @param Response $response
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct(
		$message = "",
		$code = 0,
		$response = null
	) {
		parent::__construct($message, $code);
		$this->_response = $response;

		$response->getRequest()->getConfig()->log(
			Logger::ERROR,
			'api.request.error -> '.$message,
			[
				'method' => $response->getMethod(),
				'uri' => $response->getUri()
			]
		);
	}

	/**
	 * Get the response.
	 *
	 * @since 2.0.0
	 * @return Response
	 */
	public function getResponse(): Response
	{
		return $this->_response;
	}
}
