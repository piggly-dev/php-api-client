<?php

namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;
use Piggly\ApiClient\Interfaces\RuleInterface;

/**
 * Assert if value is allowed in list.
 *
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class AllowedValuesRule implements RuleInterface
{
	/**
	 * Allowed values.
	 *
	 * @var array
	 * @since 1.1.0
	 */
	protected $_allowed_values;

	/**
	 * Constructor.
	 *
	 * @param array $_allowed_values
	 * @since 1.1.0
	 */
	public function __construct(array $allowed_values)
	{
		$this->_allowed_values = $allowed_values;
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
		if (!\in_array($value, $this->_allowed_values)) {
			throw new InvalidArgumentException(\sprintf('`%s` must be one of `%s`', $name, \implode('`, `', $this->_allowed_values)));
		}
	}
}
