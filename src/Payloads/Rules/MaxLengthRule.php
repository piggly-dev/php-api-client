<?php

namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;
use Piggly\ApiClient\Interfaces\FixableInterface;
use Piggly\ApiClient\Interfaces\RuleInterface;

/**
 * Assert if value is less than or equal to max length allowed.
 * 
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class MaxLengthRule implements RuleInterface, FixableInterface
{
	/**
	 * Max length allowed.
	 *
	 * @var int
	 * @since 1.1.0
	 */
	protected $_max;

	/**
	 * Constructor.
	 *
	 * @param integer $max_length
	 * @since 1.1.0
	 */
	public function __construct(int $max_length)
	{
		$this->_max = $max_length;
	}

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
		if (\strlen(\strval($value)) > $this->_max) {
			throw new InvalidArgumentException(\sprintf('`%s` exceeded the max length (%d) allowed', $name, $this->_max));
		}
	}
	
	/**
	 * Fix $value to expected value.
	 * Return $value fixed.
	 *
	 * @param mixed $value
	 * @since 1.1.0
	 * @return mixed
	 */
	public function fix($value) {
		if ( \is_null($value) ) {
			return $value;
		}

		return \substr($value, 0, $this->_max);
	}
}
