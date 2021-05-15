<?php
namespace Piggy\ApiClient\Client;

use Exception;

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
class ApiException extends Exception
{
	/**
	 * The HTTP body of the server response
	 * either as JSON or string.
	 *
	 * @var mixed
	 */
	protected $_body;

	/**
	 * An array with all HTTP headers from
	 * the server response.
	 *
	 * @var array<string>
	 */
	protected $_headers;

	/**
	 * The deserialized server response object.
	 *
	 * @var mixed
	 */
	protected $_object;

	/**
	 * Constructor to exception.
	 *
	 * @param string $message
	 * @param integer $code
	 * @param array<string> $headers
	 * @param mixed $body
	 * @return void
	 */
	public function __construct(
		$message = "",
		$code = 0,
		$headers = null,
		$body = null
	)
	{
		parent::__construct($message, $code);

		$this->_headers = $headers;
		$this->_body = $body;
	}

	/**
	 * Get the HTTP body of the server response 
	 * either as JSON or string.
	 *
	 * @return mixed
	 */
	public function getResponseBody ()
	{ return $this->_body ?? null; }

	/**
	 * An array with all HTTP headers from
	 * the server response.
	 *
	 * @return array<string>
	 */
	public function getResponseHeaders () : array
	{ return $this->_headers ?? []; }

	/**
	 * Set the deseralized response object 
	 * (during deserialization).
	 *
	 * @param mixed $response
	 * @return void
	 */
	public function setReponseObject ( $response )
	{ $this->_object = $response; return $this; }

	/**
	 * The deserialized server response object.
	 *
	 * @return mixed
	 */
	public function getResponseObject ()
	{ return $this->_object ?? null; }
}