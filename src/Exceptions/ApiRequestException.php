<?php
namespace Piggy\ApiClient\Exceptions;

use Exception;
use Monolog\Logger;
use Piggy\ApiClient\Configuration;

/**
 * An Api Exception which makes link to server response object,
 * HTTP headers and body.
 * 
 * @category Class
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Client
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class ApiRequestException extends Exception
{
	/**
	 * HTTP request method.
	 * @var string
	 */
	protected $_method;

	/**
	 * HTTP request uri.
	 * @var string
	 */
	protected $_uri;

	/**
	 * Constructor to exception.
	 *
	 * @param string $message
	 * @param integer $code
	 * @param string $method
	 * @param string $uri
	 * @param Configuration $config
	 * @return void
	 */
	public function __construct(
		$message = "",
		$code = 0,
		$method = null,
		$uri = null,
		$config = null
	)
	{
		parent::__construct($message, $code);

		$this->_method = $method;
		$this->_uri = $uri;

		if ( $config instanceof Configuration )
		{ $config->log(Logger::ERROR, 'api.request.error -> '.$message, ['method' => $this->_method, 'uri' => $this->_uri]); }
	}

	/**
	 * Get the HTTP method.
	 *
	 * @return mixed
	 */
	public function getHTTPMethod ()
	{ return $this->_method ?? null; }

	/**
	 * Get the HTTP uri.
	 *
	 * @return mixed
	 */
	public function getUri ()
	{ return $this->_uri ?? null; }
}