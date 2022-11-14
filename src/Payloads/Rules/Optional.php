<?php

namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;

/**
 * Assert rules and value can be null.
 * 
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class Optional extends GroupedRule
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
		if ( \is_null($value) ) {
			return;
		}

		parent::assert($name, $value);
	}
}
