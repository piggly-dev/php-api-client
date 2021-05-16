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
	 * All HTTP headers from
	 * the server response.
	 *
	 * @var HeaderBag
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
	 * @param HeaderBag|array|string $headers
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

		$this->_headers = HeaderBag::prepare($headers);
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
	 * All HTTP headers from
	 * the server response.
	 *
	 * @return HeaderBag|null
	 */
	public function getResponseHeaders () : ?HeaderBag
	{ return $this->_headers ?? null; }

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