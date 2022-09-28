<?php
namespace Piggly\ApiClient\Models;

/**
 * Abstract model with dynamic fields.
 * 
 * @since 1.0.9
 * @category Model
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Models
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
abstract class AbstractModel
{
	/**
	 * Field.
	 * 
	 * @var array
	 * @since 1.0.9
	 */
	protected $_fields = [];

	/**
	 * Get a field.
	 *
	 * @param string $name
	 * @param mixed $default
	 * @since 1.0.9
	 * @return mixed
	 */
	public function get (string $name, $default = null) {
		return $this->_fields[$name] ?? $default;
	}

	/**
	 * Set a field. If mutate_$name method
	 * exists call it before set. A mutate
	 * method must return the value mutated.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @since 1.0.9
	 * @return self
	 */
	public function set (string $name, $value) {
		if ( \method_exists($this, 'mutate_'.$name) ) {
			$value = $this->{'mutate_'.$name}($value);
		}

		$this->_fields[$name] = $value;
		return $this;
	}

	/**
	 * Check if a field exists.
	 *
	 * @param string $name
	 * @since 1.0.9
	 * @return boolean
	 */
	public function has (string $name):bool {
		return isset($this->_fields[$name]);
	}

	
	/**
	 * Export object data to an array.
	 *
	 * @since 1.0.9
	 * @return array
	 */
	abstract public function export(): array;
	
	/**
	 * Create a new model, import data to it
	 * and return the instance created.
	 *
	 * @param array $data
	 * @since 1.0.9
	 * @return self
	 */
	abstract public static function import(array $data);
}
