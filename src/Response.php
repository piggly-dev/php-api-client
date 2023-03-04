<?php

namespace Piggly\ApiClient;

use Piggly\ApiClient\Supports\HeaderBag;

/**
 * HTTP Response.
 *
 * @since 2.0.0
 * @category Class
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class Response
{
	/**
	 * Response body type is array.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	public const BODY_AS_ARRAY = 'array';

	/**
	 * Response body type is string.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	public const BODY_AS_STRING = 'string';

	/**
	 * Response body type is object.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	public const BODY_AS_OBJECT = 'object';

	/**
	 * Response body type is SplFileObject.
	 *
	 * @var string
	 * @since 2.0.0
	 */
	public const BODY_AS_SPLFILEOBJECT = '\SplFileObject';

	/**
	 * HTTP request method.
	 * @var string
	 * @since 2.0.0
	 */
	protected $_method;

	/**
	 * HTTP request uri.
	 * @var string
	 * @since 2.0.0
	 */
	protected $_uri;

	/**
	 * The HTTP body of the server response
	 * either as JSON or string.
	 *
	 * @var mixed
	 * @since 2.0.0
	 */
	protected $_body;

	/**
	 * All HTTP headers from
	 * the server response.
	 *
	 * @var HeaderBag
	 * @since 2.0.0
	 */
	protected $_headers;

	/**
	 * The deserialized server response object.
	 *
	 * @var Request
	 * @since 2.0.0
	 */
	protected $_request;

	/**
	 * The HTTP status code.
	 *
	 * @var integer
	 * @since 2.0.0
	 */
	protected $_status;

	/**
	 * cURL info data.
	 *
	 * @var mixed|null
	 * @since 2.0.0
	 */
	protected $_info;

	/**
	 * Constructor to response.
	 *
	 * @param integer $status
	 * @param string $uri
	 * @param string $method
	 * @param HeaderBag|array|string $headers
	 * @param mixed $body
	 * @param mixed $raw
	 * @param Request $request
	 * @since 1.0.0
	 * @since 1.0.6 Fixed to response error at log data
	 * @return void
	 */
	public function __construct(
		$status,
		$uri,
		$method,
		$headers = null,
		$body = null,
		$raw = null,
		$request = null
	) {
		$this->_status = $status;
		$this->_headers = !is_null($headers) ? HeaderBag::prepare($headers) : null;
		$this->_body = $body;
		$this->_method = $method;
		$this->_uri = $uri;
		$this->_info = $raw;
		$this->_request = $request;
	}

	/**
	 * Get the HTTP status code.
	 *
	 * @since 2.0.0
	 * @return integer
	 */
	public function getStatus(): int
	{
		return $this->_status;
	}

	/**
	 * Get the HTTP request method.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->_method;
	}

	/**
	 * Get the HTTP request uri.
	 *
	 * @since 2.0.0
	 * @return string
	 */
	public function getUri(): string
	{
		return $this->_uri;
	}

	/**
	 * Get the HTTP body of the server response
	 * either as JSON or string.
	 *
	 * @since 2.0.0
	 * @return mixed
	 */
	public function getBody()
	{
		return $this->_body;
	}

	/**
	 * Get all HTTP headers from
	 * the server response.
	 *
	 * @since 2.0.0
	 * @return HeaderBag
	 */
	public function getHeaders(): HeaderBag
	{
		return $this->_headers;
	}

	/**
	 * Get the deserialized server response object.
	 *
	 * @since 2.0.0
	 * @return Request
	 */
	public function getRequest(): Request
	{
		return $this->_request;
	}

	/**
	 * Get cURL info data.
	 *
	 * @since 2.0.0
	 * @return mixed|null
	 */
	public function getInfo()
	{
		return $this->_info;
	}

	/**
	 * Get all response body types as an array.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	public static function allBodyTypes(): array
	{
		return [
			self::BODY_AS_ARRAY,
			self::BODY_AS_STRING,
			self::BODY_AS_OBJECT,
			self::BODY_AS_SPLFILEOBJECT
		];
	}
}
