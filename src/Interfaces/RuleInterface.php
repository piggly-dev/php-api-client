<?php
namespace Piggly\ApiClient\Interfaces;

use InvalidArgumentException;

/**
 * Rule to validate any value.
 * 
 * @since 1.1.0
 * @category Interface
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
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
