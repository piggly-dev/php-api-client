<?php
namespace Piggy\ApiClient\Client;

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

	public function call (
		string $resourcePath,
		string $httpMethod,
		array $queryParams,
		array $postData = [],
		array $headers = [],
		string $responseType,
		string $endpointPath,
		bool $oAuth = false
	)
	{
		$uri = $this->config->getHost().'/'.ltrim($resourcePath, '/');

		if ( $oAuth )
		{ /** Implements oauth */ }
		else
		{ $headers = \array_merge($this->config->getHeaders(), $headers); }

		$_headers = [];

		// Normalize headers
		foreach ( $headers as $key => $content )
		{ $_headers[] = "$key: $content"; }

		if ( $postData && in_array('Content-Type: application/x-www-form-urlencoded') )
	}

	/**
	 * Parse HTTP headers to an array.
	 * 
	 * @param string $raw Raw HTTP Headers
	 * @return array Including all headers
	 */
	protected function _parseHTTPHeaders ( string $raw ) : array
	{
		$raw = explode('\n', $raw);
		$headers = [];

		foreach ( $raw as $header )
		{
			if ( \stripos($header, ':') === false )
			{ continue; }

			$header  = explode(':', $header, 2);
			$key     = trim($header[0]);
			$content = trim($header[1]);

			if ( !isset($headers[$key]) )
			{ $headers[$key] = $content; continue; }
			
			if ( \is_array($headers[$key]) )
			{ $headers[$key] = \array_merge($headers[$key], [$content]); continue; }

			$headers[$key] = \array_merge([ $headers[$key] ], [ $content ]);
		}

		return $headers;
	}
}