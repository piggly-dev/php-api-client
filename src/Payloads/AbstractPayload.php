<?php
namespace Piggly\ApiClient\Payloads;

use Exception;
use InvalidArgumentException;
use Piggly\ApiClient\Interfaces\FixableInterface;
use Piggly\ApiClient\Interfaces\RuleInterface;

/**
 * Abstract payload with dynamic fields.
 * 
 * @since 1.1.0
 * @category Payload
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Payloads
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
abstract class AbstractPayload
{
	/**
	 * All payload fields.
	 *
	 * @var array
	 * @since 1.1.0
	 */
	protected $_fields = [];

	/**
	 * Set data.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @since 1.1.0
	 * @return self
	 */
	protected function _set(string $name, $value)
	{
		$this->inSchema($name);
		$this->_fields[$name] = $value;
		return $this;
	}

	/**
	 * Get data.
	 *
	 * @param string $name
	 * @param mixed $default
	 * @since 1.1.0
	 * @return mixed
	 */
	protected function _get(string $name, $default = null)
	{
		$this->inSchema($name);
		return $this->has($name) ? $this->_fields[$name] : $default;
	}

	/**
	 * Has data?
	 *
	 * @param string $name
	 * @since 1.1.0
	 * @return bool
	 */
	public function has(string $name): bool
	{
		return isset($this->_fields[$name]);
	}

	/**
	 * Is empty?
	 *
	 * @param string $name
	 * @since 1.1.0
	 * @return bool
	 */
	public function empty(string $name): bool
	{
		return empty($this->_fields[$name]);
	}

	/**
	 * Get all raw fields.
	 *
	 * @since 1.1.0
	 * @return array
	 */
	public function all(): array
	{
		return $this->_fields;
	}

	/**
	 * Get all fields converting payloads
	 * to an array and removing null values.
	 *
	 * @since 1.1.0
	 * @return array
	 */
	public function toArray(): array
	{
		$arr = [];

		foreach ($this->_fields as $name => $value) {
			if (\is_null($value)) {
				continue;
			}

			if ($value instanceof AbstractPayload) {
				$arr[$name] = $value->toArray();
				continue;
			}

			$arr[$name] = \method_exists($this, 'transform_'.$name) ? $this->{'transform_'.$name}($value) : $value;
		}

		return $arr;
	}

	/**
	 * Import and return the object.
	 *
	 * @param array $body
	 * @since 1.1.0
	 * @return self
	 */
	public static function import(array $body = [])
	{
		$p = new static();

		foreach ($body as $name => $value) {
			$p->_set($name, $value);
		}

		return $p;
	}

	/**
	 * Fix values with schema.
	 *
	 * @since 1.1.0
	 * @return self
	 */
	public function fix()
	{
		foreach (static::schema() as $field => $rules) {
			if (empty($rules)) {
				continue;
			}

			foreach ($rules as $rule) {
				if ($rule instanceof FixableInterface) {
					$this->_fields[$field] = $rule->fix($this->_fields[$field]);
				}
			}
		}

		return $this;
	}

	/**
	 * Throw exception if payload is not valid.
	 *
	 * @since 1.1.0
	 * @return void
	 * @throws InvalidArgumentException
	 */
	public function assert()
	{
		foreach (static::schema() as $field => $rules) {
			if (empty($rules)) {
				continue;
			}

			foreach ($rules as $rule) {
				$rule->assert($field, $this->_fields[$field]);

				if ($this->_fields[$field] instanceof AbstractPayload) {
					$this->_fields[$field]->assert();
				}
			}
		}
	}

	/**
	 * Validate payload field.
	 *
	 * @since 1.1.0
	 * @return boolean
	 */
	public function validate(): bool
	{
		try {
			$this->assert();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Throw an exception if $needle is not in schema.
	 *
	 * @param string $needle
	 * @since 1.1.0
	 * @return void
	 * @throws InvalidArgumentException if $needle is not found
	 */
	private function inSchema(string $needle)
	{
		if (!\in_array($needle, \array_keys(static::schema()), true)) {
			throw new InvalidArgumentException(\sprintf('%s does not exist in payload schema.', $needle));
		}
	}

	/**
	 * Get payload schema.
	 *
	 * @since 1.1.0
	 * @return array<array<RuleInterface>>
	 */
	abstract protected static function schema(): array;
}