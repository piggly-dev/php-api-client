<?php

namespace Piggly\ApiClient\Interfaces;

/**
 * Interface for application with API data.
 *
 * @since 2.2.0
 * @category Interfaces
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
interface CredentialInterface
{
	/**
	 * Get access token type.
	 *
	 * @since 2.2.0
	 * @return string
	 */
	public function getTokenType(): string;

	/**
	 * Get access token.
	 *
	 * @since 2.2.0
	 * @return string
	 */
	public function getAccessToken(): string;

	/**
	 * Return if credential has expired.
	 *
	 * @since 2.2.0
	 * @return boolean
	 */
	public function isExpired(): bool;

	/**
	 * Export object data to an array.
	 *
	 * @since 2.2.0
	 * @return array
	 */
	public function export(): array;

	/**
	 * Create a new application model with data.
	 *
	 * @param array $data
	 * @since 2.2.0
	 * @return self
	 */
	public static function import(array $data);
}
