<?php
namespace Piggly\ApiClient\Supports;

use InvalidArgumentException;
 
/**
 * Class to better manages HTTP headers.
 * 
 * @since 1.0.0
 * @category Class
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Supports
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class HeaderBag
{
	/**
	 * Headers.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $_headers = [];

	/**
	 * Raws headers.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $_raws = [];

	/**
	 * Constructor with default headers, including $key and $content.
	 *
	 * @param array $headers
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct ( array $headers = [] )
	{ 
		foreach ( $headers as $key => $content )
		{ $this->add($key, $content); }
	}

	/**
	 * Add a raw header. Adding it, will not be possible to
	 * use this class methods to manipulate it before.
	 *
	 * @param string $raw
	 * @since 1.0.0
	 * @return HeaderBag
	 */
	public function raw ( string $raw )
	{ $this->_raws[] = $raw; return $this; }

	/**
	 * Add a new header to bag.
	 *
	 * @param string $key
	 * @param string|array $content
	 * @since 1.0.0
	 * @return HeaderBag
	 * @throws InvalidArgumentException If $content is not string or array.
	 */
	public function add ( string $key, $content )
	{ $this->_headers[\strtolower($key)] = $this->parseContent($content); return $this; }

	/**
	 * Append $content to Header $key. If Header $key
	 * does not exist, then creates it.
	 *
	 * @param string $key
	 * @param string|array $content
	 * @since 1.0.0
	 * @return HeaderBag
	 * @throws InvalidArgumentException If $content is not string or array.
	 */
	public function append ( string $key, $content )
	{
		if ( !$this->has($key) )
		{ return $this->add($key, $content); }

		$this->_headers[\strtolower($key)] = \array_merge(
			$this->get($key), 
			$this->parseContent($content)
		); 

		return $this;
	}

	/**
	 * Get the Header contents.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @since 1.0.0
	 * @return array|mixed
	 */
	public function get ( string $key, $default = null )
	{ return $this->_headers[\strtolower($key)] ?? $default; }

	/**
	 * Remove the Header content.
	 *
	 * @param string $key
	 * @since 1.0.0
	 * @return HeaderBag
	 */
	public function remove ( string $key )
	{ unset($this->_headers[\strtolower($key)]); return $this; }

	/**
	 * Merge current headers to new ones.
	 * Current headers may be replaced by $headers.
	 *
	 * @param HeaderBag|array|string $headers
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return boolean
	 */
	public function has ( string $key ) : bool
	{ return isset($this->_headers[\strtolower($key)]); }

	/**
	 * Check if $content is present at Header content.
	 *
	 * @param string $key
	 * @param string $content
	 * @since 1.0.0
	 * @return boolean
	 */
	public function is ( string $key, string $content ) : bool
	{
		if ( !$this->has($key) )
		{ return false; }

		return \stripos(implode(', ', $this->get($key)), $content) !== false;
	}

	/**
	 * Prepare $headers argument transforming appending
	 * it to current headers object.
	 *
	 * @param HeaderBag|array|string $headers
	 * @since 1.0.0
	 * @return HeaderBag
	 */
	public function apply ( $headers )
	{
		if ( $headers instanceof HeaderBag )
		{ return $this->mergeWith($headers); }

		if ( \is_array($headers) )
		{ 
			foreach ( $headers as $key => $content )
			{ $this->append($key, $content); }

			return $this;
		}

		if ( \is_string($headers) )
		{ return $this->parseRaw($headers); }

		throw new InvalidArgumentException('Unexpected headers, it must be one of: string, array or HeaderBag object.');
	}

	/**
	 * Prepare an array to CURLOPT_HTTPHEADER.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function cURL () : array
	{
		$headers = [];

		foreach ( $this->_headers as $key => $content )
		{ 
			$content = \is_array($content) ? implode(', ', $content) : (string)$content;
			$headers[] = "$key: $content"; 
		}

		foreach ( $this->_raws as $header )
		{ $headers[] = $header; }

		return $headers;
	}

	/**
	 * Get all headers.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function all () : array
	{ return $this->_headers; }

	/**
	 * Prepare $headers argument transforming it
	 * to a HeaderBag object.
	 *
	 * @param HeaderBag|array|string $headers
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return HeaderBag
	 */
	protected static function fromString ( string $raw ) : HeaderBag
	{
		$headers = new HeaderBag();
		return $headers->parseRaw($raw);
	}

	/**
	 * Parse $content to an array of contents.
	 *
	 * @param string|array $content
	 * @since 1.0.0
	 * @return array
	 */
	private function parseContent ( $content ) : array
	{
		if ( !\is_string($content) && !\is_array($content) )
		{ throw new InvalidArgumentException('Header content is expecting a string or an array value.'); }

		if ( \is_array($content) )
		{ return $content; }

		if ( $this->isJson($content) )
		{ return [$content]; }

		return \array_map(
			function ( $content ) {
				return trim($content);
			},
			explode(',', $content)
		); 
	}

	/**
	 * Parse raw headers string to headers.
	 *
	 * @param string $raw
	 * @since 1.0.0
	 * @return HeaderBag
	 */
	private function parseRaw ( string $raw )
	{
		$raw = explode("\n", $raw);

		foreach ( $raw as $header )
		{
			if ( \stripos($header, ':') === false )
			{ continue; }

			$header  = explode(':', $header, 2);
			$this->append(trim($header[0]), trim($header[1]));
		}

		return $this;
	}

	/**
	 * Check if $value is a json string.
	 *
	 * @param mixed $value
	 * @since 1.0.0
	 * @since 1.0.4 Not json when empty
	 * @return bool
	 */
	private function isJson ( $value ) 
	{
		if ( !\is_string( $value ) || empty($value) ) 
		{ return false; }

		if ( '{' != $value[0] && '[' != $value[0] ) 
		{ return false; }

		$json_data = json_decode($value, true);

		if ( json_last_error() !== JSON_ERROR_NONE )
		{ return false; }

		return true;
	}
}