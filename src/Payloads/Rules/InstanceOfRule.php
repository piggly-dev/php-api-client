<?php

namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;

/**
 * Assert if value is instance of expected.
 * 
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class InstanceOfRule implements RuleInterface
{
	/**
	 * Class name.
	 *
	 * @var string
	 * @since 1.1.0
	 */
	protected $_class_name;

	/**
	 * Constructor.
	 *
	 * @param string $class_name
	 * @since 1.1.0
	 */
	public function __construct(string $class_name)
	{
		$this->_class_name = $class_name;
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
		// Use if required rule to ensure value is set when value cannot be empty
		if (empty($value)) {
			return;
		}

		if (!($value instanceof $this->_class_name)) {
			throw new InvalidArgumentException(\sprintf('`%s` must be instance of %s', $name, $this->_class_name));
		}
	}
}
