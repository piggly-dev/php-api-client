<?php
namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;

/**
 * Rule to validate any value.
 * 
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
interface RuleInterface
{
	/**
	 * Assert value and must throw an
	 * exception if invalid.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @since 1.1.0
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function assert(string $name, $value);
}
