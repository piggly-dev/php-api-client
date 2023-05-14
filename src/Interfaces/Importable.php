<?php

namespace Pgly\PagarMe\Api\Interfaces;

/**
 * Object that can be imported from an array.
 *
 * @since 2.1.1
 * @category Interfaces
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @copyright 2023 Piggly Lab
 */
interface Importable
{
	/**
	 * Import data from an array.
	 *
	 * @param array $data
	 * @return mixed
	 * @since 2.1.1
	 */
	public static function import(array $data);
}
