<?php

namespace Piggly\ApiClient\Payloads\Rules;

use InvalidArgumentException;
use Piggly\ApiClient\Interfaces\FixableInterface;
use Piggly\ApiClient\Interfaces\RuleInterface;

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
abstract class GroupedRule implements RuleInterface, FixableInterface
{
	/**
	 * Rules.
	 *
	 * @var array<RuleInterface>
	 * @since 1.1.0
	 */
	protected $_rules;

	/**
	 * Constructor.
	 *
	 * @param array<RuleInterface> $_rules
	 * @since 1.1.0
	 */
	public function __construct(array $_rules)
	{
		$this->_rules = $_rules;
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
		foreach ($this->_rules as $rule) {
			$rule->assert($name, $value);
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
	public function fix($value)
	{
		foreach ($this->_rules as $rule) {
			if ($rule instanceof FixableInterface) {
				$value = $rule->fix($value);
			}
		}

		return $value;
	}
}
