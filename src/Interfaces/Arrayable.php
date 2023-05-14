<?php

namespace Piggly\ApiClient\Interfaces;

/**
 * Object that can be converted to array.
 *
 * @since 2.1.1
 * @category Interfaces
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @copyright 2023 Piggly Lab
 */
interface Arrayable
{
	/**
	 * Convert object to array.
	 *
	 * @return array
	 * @since 2.1.1
	 */
	public function toArray(): array;
}
