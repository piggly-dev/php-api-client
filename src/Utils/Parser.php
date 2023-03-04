<?php

namespace Piggly\ApiClient\Utils;

use Piggly\ApiClient\Response;

/**
 * Parser utilities.
 *
 * @since 2.0.0
 * @category Utils
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class Parser
{
	/**
	 * Return JSON decoded as an associative array or raw data.
	 *
	 * @param mixed $data
	 * @param string $expectedType array, object, string or \SplFileObject
	 * @see Response
	 * @since 2.0.0
	 * @return mixed
	 */
	public static function decodeJSONOrRaw($data, string $expectedType = 'array')
	{
		if (!\is_string($data) || ($expectedType !== Response::BODY_AS_ARRAY && $expectedType !== Response::BODY_AS_OBJECT)) {
			return $data;
		}

		$decoded = \json_decode($data, $expectedType === Response::BODY_AS_ARRAY);

		if (\json_last_error() !== JSON_ERROR_NONE) {
			return $data;
		}

		return $decoded;
	}

	/**
	 * Convert any data value to string.
	 *
	 * @param mixed $data
	 * @since 2.0.0
	 * @return string
	 */
	public static function anyToString($data)
	{
		return \is_object($data) ? \serialize($data) : (\is_array($data) ? \json_encode($data) : \strval($data));
	}
}
