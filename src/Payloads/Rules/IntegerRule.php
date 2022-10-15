<?php

namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;

/**
 * Assert if value is integer.
 * 
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class IntegerRule implements RuleInterface
{
	/**
	 * Assert value and must throw an
	 * exception if invalid.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function assert(string $name, $value)
	{
		if (!\is_int($value)) {
			throw new InvalidArgumentException(\sprintf('`%s` must be integer', $name));
		}
	}
}
