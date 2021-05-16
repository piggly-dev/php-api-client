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
	private $_headers = [];

	/**
	 * Add a new header to bag.
	 *
	 * @param string $key
	 * @param string|array $content
	 * @return HeaderBag
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