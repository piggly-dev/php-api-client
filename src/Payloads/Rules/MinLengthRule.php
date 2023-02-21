<?php

namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;
use Piggly\ApiClient\Interfaces\RuleInterface;

/**
 * Assert if value is less than or equal to min length allowed.
 *
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class MinLengthRule implements RuleInterface
{
	/**
	 * Min length allowed.
	 *
	 * @var int
	 * @since 1.1.0
	 */
	protected $_min;

	/**
	 * Constructor.
	 *
	 * @param integer $min_length
	 * @since 1.1.0
	 */
	public function __construct(int $min_length)
	{
		$this->_min = $min_length;
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
		if (\strlen(\strval($value)) < $this->_min) {
			throw new InvalidArgumentException(\sprintf('`%s` must has (%d) length or more', $name, $this->_min));
		}
	}
}
