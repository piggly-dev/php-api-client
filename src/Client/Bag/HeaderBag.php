<?php
namespace Piggy\ApiClient\Client;

use InvalidArgumentException;

class HeaderBag
{
	/**
	 * Headers.
	 *
	 * @var array
	 */
	private $_headers;

	/**
	 * Raws headers.
	 *
	 * @var array
	 */
	private $_raws = [];

	/**
	 * Constructor with default headers.
	 *
	 * @param array $headers
	 * @return void
	 */
	public function __construct ( array $headers = [] )
	{ $this->_headers = $headers; }

	/**
	 * Add a raw header. Adding it, will not be possible to
	 * use this class methods to manipulate it before.
	 *
	 * @param string $raw
	 * @return HeaderBag
	 */
	public function raw ( string $raw )
	{ $this->_raws[] = $raw; return $this; }

	/**
	 * Add a new header to bag.
	 *
	 * @param string $key
	 * @param string|array $content
	 * @return HeaderBag
	 * @throws InvalidArgumentException If $content is not string or array.
	 */
	public function add ( string $key, $content )
	{ 
		if ( !\is_string($content) && !\is_array($content) )
		{ throw new InvalidArgumentException('Header content is expecting a string or an array value.'); }

		if ( \is_array($content) )
		{ $content = implode(', ', $content); }

		$this->_headers[$key] = $content; 
		return $this; 
	}

	/**
	 * Get the Header content.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get ( string $key, $default = null )
	{ return $this->_headers[$key] ?? $default; }

	/**
	 * Remove the Header content.
	 *
	 * @param string $key
	 * @return HeaderBag
	 */
	public function remove ( string $key )
	{ unset($this->_headers[$key]); return $this; }

	/**
	 * Merge current headers to new ones.
	 *
	 * @param HeaderBag|array|string $headers
	 * @return HeaderBag
	 */
	public function mergeWith ( $headers )
	{
		$headers = static::prepare($headers);
		$this->_headers = \array_merge($this->_headers, $headers->all());
		return $this;
	}

	/**
	 * Check it has the Header $key.
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function has ( string $key ) : bool
	{ return isset($this->_headers[$key]); }

	/**
	 * Check if $content is present at Header content.
	 *
	 * @param string $key
	 * @param string $content
	 * @return boolean
	 */
	public function is ( string $key, string $content ) : bool
	{
		if ( !$this->has($key) )
		{ return false; }

		return \stripos($this->get($key), $content) !== false;
	}

	/**
	 * Prepare an array to CURLOPT_HTTPHEADER.
	 *
	 * @return array
	 */
	public function cURL () : array
	{
		$headers = [];

		foreach ( $this->_headers as $key => $content )
		{ $headers[] = "$key: $content"; }

		foreach ( $this->_raws as $header )
		{ $headers[] = $header; }

		return $headers;
	}

	/**
	 * Get all headers.
	 *
	 * @return array
	 */
	public function all () : array
	{ return $this->_headers; }

	/**
	 * Prepare $headers argument transforming it
	 * to a HeaderBag object.
	 *
	 * @param HeaderBag|array|string $headers
	 * @return HeaderBag
	 */
	public static function prepare ( $headers ) : HeaderBag
	{
		if ( $headers instanceof HeaderBag )
		{ return $headers; }

		if ( \is_array($headers) )
		{ return new HeaderBag($headers); }

		if ( \is_string($headers) )
		{ return static::fromString($headers); }

		throw new InvalidArgumentException('Unexpected headers, it must be one of: string, array or HeaderBag object.');
	}

	/**
	 * Get headers from CURL headers $raw string
	 *
	 * @param string $raw
	 * @return HeaderBag
	 */
	protected static function fromString ( string $raw ) : HeaderBag
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

		return new HeaderBag($headers);
	}
}