<?php
namespace Piggy\ApiClient\Client;

use CURLFile;
use InvalidArgumentException;
use Piggy\ApiClient\Exceptions\ApiRequestException;
use Piggy\ApiClient\Exceptions\ApiResponseException;

class ApiClient
{
	/**
	 * PATCH HTTP request method.
	 *
	 * @var string
	 */
	public static $PATCH = 'PATCH';

	/**
	 * POST HTTP request method.
	 *
	 * @var string
	 */
	public static $POST = 'POST';

	/**
	 * GET HTTP request method.
	 *
	 * @var string
	 */
	public static $GET = 'GET';

	/**
	 * HEAD HTTP request method.
	 *
	 * @var string
	 */
	public static $HEAD = 'HEAD';

	/**
	 * OPTIONS HTTP request method.
	 *
	 * @var string
	 */
	public static $OPTIONS = 'OPTIONS';

	/**
	 * PUT HTTP request method.
	 *
	 * @var string
	 */
	public static $PUT = 'PUT';

	/**
	 * DELETE HTTP request method.
	 *
	 * @var string
	 */
	public static $DELETE = 'DELETE';

	/**
	 * Rest API config for this ApiClient.
	 *
	 * @var Configuration
	 */
	protected $config;

	/**
	 * URL Path.
	 *
	 * @var string
	 */
	protected $_path;

	/**
	 * HTTP method.
	 *
	 * @var string
	 */
	protected $_method;

	/**
	 * Query parameters normalized.
	 *
	 * @var string
	 */
	protected $_query;

	/**
	 * Post data.
	 *
	 * @var array|object
	 */
	protected $_data;

	/**
	 * HTTP Headers.
	 *
	 * @var HeaderBag
	 */
	protected $_headers;

	/**
	 * Response type.
	 *
	 * @var string
	 */
	protected $_responseType = 'array';

	/**
	 * Is OAuth enabled?
	 *
	 * @var bool
	 */
	protected $_oAuth;

	/**
	 * Constructor to class
	 *
	 * @param Configuration $config Rest API config for this ApiClient.
	 * @return void
	 */
	public function __construct (
		Configuration $config = null
	)
	{
		if ( $config === null ) 
		{ $config = Configuration::getDefault(); }

		$this->config = $config;
	}

	/**
	 * Rest API config for this ApiClient.
	 *
	 * @return Configuration
	 */
	public function getConfig ()
	{ return $this->config; }

	/**
	 * Prepare api key getting it by $identifier and addind
	 * prefix to it if set.
	 *
	 * @param string $identifier ID to key. (authentication scheme)
	 * @return string|null
	 */
	public function prepareApiKey ( string $identifier ) : ?string
	{
		list($prefix, $key) = $this->config->getApiKey($identifier);

		if ( is_null($key) )
		{ return null; }

		if ( !is_null($prefix) )
		{ return $prefix.' '.$key; }

		return $key;
	}

	/**
	 * Prepare a DELETE request.
	 *
	 * @param string $path
	 * @param array|object $body
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	public function delete (
		string $path,
		$body = [],
		$query = null,
		$headers = null,
		$responseType = null
	)
	{ return $this->_withBody(self::$DELETE, $path, $body, $query, $headers, $responseType); }

	/**
	 * Prepare a HEAD request.
	 *
	 * @param string $path
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	public function head (
		string $path,
		$query = null,
		$headers = null,
		$responseType = null
	)
	{ return $this->_noBody(self::$HEAD, $path, $query, $headers, $responseType); }

	/**
	 * Prepare a GET request.
	 *
	 * @param string $path
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	public function get (
		string $path,
		$query = null,
		$headers = null,
		$responseType = null
	)
	{ return $this->_noBody(self::$GET, $path, $query, $headers, $responseType); }

	/**
	 * Prepare an OPTIONS request.
	 *
	 * @param string $path
	 * @param array|object $body
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	public function options (
		string $path,
		$body = [],
		$query = null,
		$headers = null,
		$responseType = null
	)
	{ return $this->_withBody(self::$OPTIONS, $path, $body, $query, $headers, $responseType); }

	/**
	 * Prepare a PATCH request.
	 *
	 * @param string $path
	 * @param array|object $body
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	public function patch (
		string $path,
		$body = [],
		$query = null,
		$headers = null,
		$responseType = null
	)
	{ return $this->_withBody(self::$PATCH, $path, $body, $query, $headers, $responseType); }

	/**
	 * Prepare a POST request.
	 *
	 * @param string $path
	 * @param array|object $body
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	public function post (
		string $path,
		$body = [],
		$query = null,
		$headers = null,
		$responseType = null
	)
	{ return $this->_withBody(self::$POST, $path, $body, $query, $headers, $responseType); }

	/**
	 * Prepare a PUT request.
	 *
	 * @param string $path
	 * @param array|object $body
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	public function put (
		string $path,
		$body = [],
		$query = null,
		$headers = null,
		$responseType = null
	)
	{ return $this->_withBody(self::$PUT, $path, $body, $query, $headers, $responseType); }

	/**
	 * Set current HTTP headers.
	 *
	 * @param HeaderBag|array|string $headers
	 * @return ApiClient
	 */
	public function headers ( $headers )
	{ $this->_headers = HeaderBag::prepare($headers); return $this; }

	/**
	 * Set current POST data.
	 *
	 * @param array|object $postData May be an array or object containing properties.
	 * @return ApiClient
	 * @throws ApiRequestException
	 */
	public function data ( $postData )
	{ 
		if ( !\is_object($postData) && !\is_array($postData) )
		{ 
			throw new ApiRequestException(
				'Post data must be an array or an object containing properties.',
				5,
				$this->_method,
				$this->getUri(),
				$this->config
			); 
		}

		$this->_data = $postData; 
		return $this; 
	}

	/**
	 * Set URL query parameters. It uses the http_build_query()
	 * PHP function.
	 *
	 * @see https://www.php.net/manual/pt_BR/function.http-build-query
	 * @param array|object $query May be an array or object containing properties.
	 * @return ApiClient
	 * @throws ApiRequestException
	 */
	public function query ( $query )
	{ 
		if ( !\is_object($query) && !\is_array($query) )
		{ 
			throw new ApiRequestException(
				'Query parameters must be an array or an object containing properties.',
				5,
				$this->_method,
				$this->getUri(),
				$this->config
			); 
		}

		$this->_query = \http_build_query($query); return $this; 
	}

	/**
	 * Set expected response type. By default it's 'array'.
	 * Must be one of: string, array or \SplFileObject.
	 *
	 * @param string $type
	 * @return ApiClient
	 * @throws ApiRequestException
	 */
	public function responseType ( string $type )
	{
		if ( !\in_array($type, ['\SplFileObject', 'string', 'array'], true) === false )
		{ 
			throw new ApiRequestException(
				'Response type must be one of: string, array or \SplFileObject.',
				5,
				$this->_method,
				$this->getUri(),
				$this->config
			); 
		}

		$this->_responseType = $type;
		return $this;
	}

	/**
	 * Set if oAuth is enabled or not.
	 *
	 * @param boolean $oAuth
	 * @return ApiClient
	 */
	public function oAuth ( bool $oAuth )
	{ $this->_oAuth = $oAuth; return $this; }

	/**
	 * Does an API call.
	 *
	 * @return array In format [$http_body, $http_code, $http_header].
    * @throws ApiRequestException something went wrong to request
    * @throws ApiResponseException on a non 2xx response
	 */
	public function call ()
	{
		if ( empty($this->_method) || empty($this->_path) )
		{ 
			throw new ApiRequestException(
				'Cannot do an API call before set Request HTTP method and/or path.',
				10,
				$this->_method,
				$this->getUri(),
				$this->config
			); 
		}

		// Prepare headers
		$headers = $this->config->headers()->mergeWith($this->_headers);
		// Prepare post data
		$postData = $this->preparePostData($headers, $this->_data);

		// Curl starter
		$cURL = \curl_init();

		// Timeout if needed
		if ( $this->config->getTimeout() !== 0 )
		{ \curl_setopt($cURL, \CURLOPT_TIMEOUT, $this->config->getTimeout()); }

		// Connection timeout if needed
		if ( $this->config->getConnectionTimeout() !== 0 )
		{ \curl_setopt($cURL, \CURLOPT_CONNECTTIMEOUT, $this->config->getConnectionTimeout()); }

		// Return result as string, or true instead
		\curl_setopt($cURL, \CURLOPT_RETURNTRANSFER, true);
		// Export headers to cURL format
		\curl_setopt($cURL, \CURLOPT_HTTPHEADER, $headers->cURL());

		// Disable SSL verification if needed
		if ( !$this->config->shouldVerifySSL() )
		{
			\curl_setopt($cURL, \CURLOPT_SSL_VERIFYPEER, 0);
			\curl_setopt($cURL, \CURLOPT_SSL_VERIFYHOST, 0);
		}

		// Proxy settings

		if ($this->config->getProxyHost()) 
		{ \curl_setopt($cURL, \CURLOPT_PROXY, $this->config->getProxyHost()); }

		if ($this->config->getProxyPort()) 
		{ \curl_setopt($cURL, \CURLOPT_PROXYPORT, $this->config->getProxyPort()); }

		if ($this->config->getProxyType()) 
		{ \curl_setopt($cURL, \CURLOPT_PROXYTYPE, $this->config->getProxyType()); }

		if ($this->config->getProxyUser()) 
		{ \curl_setopt($cURL, \CURLOPT_PROXYUSERPWD, $this->config->getProxyUser().':'.$this->config->getProxyPassword()); }
	
		switch ( $this->_method )
		{
			case self::$DELETE:
				\curl_setopt($cURL, \CURLOPT_CUSTOMREQUEST, 'DELETE');
				\curl_setopt($cURL, \CURLOPT_POSTFIELDS, $postData);
				break;
			case self::$GET:
				break;
			case self::$HEAD:
				\curl_setopt($cURL, \CURLOPT_NOBODY, true);
				break;
			case self::$OPTIONS:
				\curl_setopt($cURL, \CURLOPT_CUSTOMREQUEST, 'OPTIONS');
				\curl_setopt($cURL, \CURLOPT_POSTFIELDS, $postData);
				break;
			case self::$PATCH:
				\curl_setopt($cURL, \CURLOPT_CUSTOMREQUEST, 'PATCH');
				\curl_setopt($cURL, \CURLOPT_POSTFIELDS, $postData);
				break;
			case self::$POST:
				\curl_setopt($cURL, \CURLOPT_POST, true);
				\curl_setopt($cURL, \CURLOPT_POSTFIELDS, $postData);
				break;
			case self::$PUT:
				\curl_setopt($cURL, \CURLOPT_CUSTOMREQUEST, 'PUT');
				\curl_setopt($cURL, \CURLOPT_POSTFIELDS, $postData);
				break;
		}

		// Uri
		$uri = $this->getUri();
		\curl_setopt($cURL, \CURLOPT_URL, $uri);
		// User agent
		\curl_setopt($cURL, \CURLOPT_USERAGENT, $this->config->getUserAgent());

		// Curl debug
		if ( $this->config->isDebugging() )
		{ \curl_setopt($cURL, \CURLOPT_VERBOSE, 1); }
		else
		{ \curl_setopt($cURL, \CURLOPT_VERBOSE, 0); }

		// Get HTTP response headers
		\curl_setopt($cURL, \CURLOPT_HEADER, 1);

		// Make the request
		$response = \curl_exec($cURL);
		// Header size
		$http_header_size = \curl_getinfo($cURL, CURLINFO_HEADER_SIZE);

		/** @var HeaderBag $http_header Extract headers. */
		$http_header = HeaderBag::prepare(\substr($response, 0, $http_header_size));
		/** @var string $http_body Extract body. */
		$http_body = \substr($response, $http_header_size);

		// Get response info
		$response_info = \curl_getinfo($cURL);

		// Request error
		if ( $response_info['http_code'] === 0 )
		{
			$message = \curl_error($cURL);

			if ( !empty($message) )
			{ $message = \sprintf('API call to `%s` failed: %s', $uri, $message); }
			else
			{ $message = \sprintf('API call to `%s` failed: Unknown reasion.', $uri); }

			$e = new ApiResponseException(
				$message,
				0,
				$http_header,
				$http_body,
				$this->_method,
				$this->getUri(),
				$this->config
			);

			$e->setReponseObject($response_info);
			throw $e;
		}

		// Invalid response
		if ( $response_info['http_code'] < 200 || $response_info['http_code'] >= 300 )
		{
			$data = \json_decode( $http_body );

			// Cannot decode json, restore raw body
			if ( \json_last_error() > 0 )
			{ $data = $http_body; }

			throw new ApiResponseException(
				\sprintf(
					'Error while requesting server, received a non successful HTTP code `%s` with response body: %s.',
					$response_info['http_code'],
					\is_object($data) ? serialize($data) : $data
				),
				$response_info['http_code'],
				$http_header,
				$data,
				$this->_method,
				$this->getUri(),
				$this->config
			);
		}

		// Raw body according to response type
		if ($this->_responseType === '\SplFileObject' || $this->_responseType === 'string') 
		{ return [$http_body, $response_info['http_code'], $http_header]; }

		$data = \json_decode( $http_body );

		// Cannot decode json, restore raw body
		if ( \json_last_error() > 0 )
		{ $data = $http_body; }

		return [$data, $response_info['http_code'], $http_header];
	}
	
	/**
	 * Set current HTTP method.
	 * 
	 * This method is protected to force 
	 * use of request methods.
	 *
	 * @param string $httpMethod
	 * @return ApiClient
	 * @throws ApiException
	 */
	protected function method ( string $httpMethod )
	{ 
		if ( \in_array($httpMethod, [self::$DELETE, self::$GET, self::$HEAD, self::$OPTIONS, self::$PATCH, self::$POST, self::$PUT]) === false )
		{ throw new ApiException(\sprintf('HTTP Method `%s` is not recognized', $httpMethod)); }

		$this->_method = $httpMethod; 
		return $this; 
	}

	/**
	 * Set current URL path.
	 * 
	 * This method is protected to force 
	 * use of request methods.
	 *
	 * @param string $path
	 * @return ApiClient
	 */
	protected function path ( string $path )
	{ $this->_path = ltrim($path, '/'); return $this; }

	/**
	 * Prepare a Request without body.
	 *
	 * @param string $method
	 * @param string $path
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	protected function _noBody (
		string $method,
		string $path,
		$query = null,
		$headers = null,
		$responseType = null
	)
	{
		$this
			->method($method)
			->path($path);

		if ( !\is_null($query) )
		{ $this->query($query); }

		if ( !\is_null($headers) )
		{ $this->headers($headers); }

		if ( !\is_null($responseType) )
		{ $this->responseType($responseType); }

		return $this;
	}

	/**
	 * Prepare a Request with body.
	 *
	 * @param string $method
	 * @param string $path
	 * @param array|object $data
	 * @param array|object $query
	 * @param HeaderBag|array|string $headers
	 * @param string $responseType
	 * @return void
	 */
	protected function _withBody (
		string $method,
		string $path,
		$data,
		$query = null,
		$headers = null,
		$responseType = null
	)
	{
		$this
			->method($method)
			->path($path);

		if ( !\is_null($query) )
		{ $this->query($query); }

		if ( !\is_null($headers) )
		{ $this->headers($headers); }

		if ( !\is_null($responseType) )
		{ $this->responseType($responseType); }

		if ( \is_null($data) )
		{ throw new InvalidArgumentException(\sprintf('The HTTP method `%s` does not support a NULL body.', $this->_method)); }

		$this->data($data);

		return $this;
	}

	/**
	 * Prepare post data according with Content-Type header.
	 *
	 * @param HeaderBag $headers
	 * @param array|object $postData
	 * @return string|array|object
	 */
	protected function preparePostData ( HeaderBag $headers, $postData )
	{
		if ( $postData && $headers->is('content-type', 'application/x-www-form-urlencoded') )
		{ return \http_build_query($postData); }

		if ( (\is_object($postData) || \is_array($postData)) && !$headers->is('content-type', 'multipart/form-data') )
		{ return json_encode($postData); }

		return $postData;
	}
	
	/**
	 * Get current URI.
	 *
	 * @return string
	 */
	protected function getUri () : string
	{ 
		$uri = $this->config->getHost().'/'.($this->_path ?? ''); 

		if ( !empty($this->_query) )
		{ return \sprintf('%s?%s', $uri, $this->_query); }

		return $uri;
	}
}