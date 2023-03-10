<?php

namespace Piggly\ApiClient\Interfaces;

/**
 * Interface for application with API data.
 *
 * @since 2.1.0
 * @category Interfaces
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
interface ApplicationInterface
{
	/**
	 * Return if has a valid access token.
	 *
	 * @since 2.1.0
	 * @return boolean
	 */
	public function isAccessTokenValid(): bool;

	/**
	 * Return if application is debugging.
	 *
	 * @since 2.1.0
	 * @return boolean
	 */
	public function isDebugging(): bool;

	/**
	 * Check if current env is equal to $expected.
	 *
	 * @param string $expected
	 * @since 2.1.0
	 * @return boolean
	 */
	public function isEnv(string $expected): bool;

	/**
	 * Must return the Environment object according to
	 * the current Environment.
	 *
	 * @since 2.1.0
	 * @return EnvInterface
	 */
	public function createEnvironment(): EnvInterface;

	/**
	 * Export object data to an array.
	 *
	 * @since 2.1.0
	 * @return array
	 */
	public function export(): array;

	/**
	 * Create a new application model with data.
	 *
	 * @param array $data
	 * @since 2.1.0
	 * @return self
	 */
	public static function import(array $data);
}
