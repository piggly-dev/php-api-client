<?php

namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;
use Piggly\ApiClient\Interfaces\RuleInterface;

/**
 * Assert if value is float.
 * 
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class FloatRule implements RuleInterface
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
		if (!\is_float($value)) {
			throw new InvalidArgumentException(\sprintf('`%s` must be float', $name));
		}
	}
}
