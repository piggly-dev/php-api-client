<?php
namespace Piggly\ApiClient\Interfaces;

/**
 * Indicates that object have fix operations.
 * 
 * @since 1.1.0
 * @category Interface
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
interface FixableInterface
{
	/**
	 * Fix $value to expected value.
	 * Return $value fixed.
	 *
	 * @param mixed $value
	 * @since 1.1.0
	 * @return mixed
	 */
	public function fix($value);
}
