<?php
namespace Piggly\ApiClient\Models;

use RuntimeException;

/**
 * Model for an application. The expected fields are:
 * 
 * environment
 * debug_mode
 * client_id
 * client_secret
 * credential
 * certificate
 * 
 * @since 1.0.8
 * @category Interfaces
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class ApplicationModel extends AbstractModel
{
	/**
	 * Test enviroment.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	public const ENV_TEST = 'test';

	/**
	 * Sandbox/homologation enviroment.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	public const ENV_HOMOL = 'homol';

	/**
	 * Production enviroment.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	public const ENV_PRODUCTION = 'prod';

	/**
	 * Mutate env validating if has valid value.
	 *
	 * @param string|array $value
	 * @since 1.0.8
	 * @return string
	 * @throws RuntimeException
	 */
	protected function mutate_environment($value)
	{
		if ( \in_array($value, [static::ENV_TEST, static::ENV_HOMOL, static::ENV_PRODUCTION])) {
			return $value;
		}

		throw new RuntimeException('Invalid environment date.');
	}

	/**
	 * Mutate debug mode to boolean.
	 *
	 * @param mixed $value
	 * @since 1.0.8
	 * @return boolean
	 */
	protected function mutate_debug_mode($value)
	{
		return \boolval($value);
	}

	/**
	 * Mutate credential to CredentialModel.
	 *
	 * @param mixed $value
	 * @since 1.0.8
	 * @return CredentialModel
	 * @throws RuntimeException
	 */
	protected function mutate_credential($value)
	{
		if ( $value instanceof CredentialModel ) {
			return $value;
		} elseif ( \is_array($value) ) {
			return CredentialModel::import($value);
		}

		throw new RuntimeException('Invalid credential value.');
	}

	/**
	 * Mutate certificate to a valid array.
	 *
	 * @param mixed $value
	 * @since 1.0.8
	 * @return array
	 * @throws RuntimeException
	 */
	protected function mutate_certificate ($value) {
		if ( empty($value) ) {
			// can be empty
			return $value;
		}

		if ( \is_array($value) ) {
			if ( isset($value['key'], $value['cert']) ) {
				return $value;
			}
		}

		throw new RuntimeException('Invalid certificate value.');
	}

	/**
	 * Return if has a valid access token.
	 *
	 * @since 1.0.8
	 * @return boolean
	 */
	public function isAccessTokenValid () : bool {
		if ( !$this->has('credential') ) {
			return false;
		}

		if ( !$this->get('credential')->has('access_token') ) {
			return false;
		}

		return !$this->get('credential')->isExpired();
	}

	/**
	 * Export object data to an array.
	 *
	 * @since 1.0.8
	 * @return array
	 */
	public function export(): array
	{
		return [
			'environment' => $this->get('environment'),
			'debug_mode' => $this->get('debug_mode'),
			'client_id' => $this->get('client_id'),
			'client_secret' => $this->get('client_secret'),
			'credential' => $this->has('credential') ? $this->get('credential')->export() : [],
			'certificate' => $this->get('certificate', []),
		];
	}

	/**
	 * Create a new application model with data.
	 *
	 * @param array $data
	 * @since 1.0.8
	 * @return self
	 */
	public static function import(array $data)
	{
		$m = new ApplicationModel();

		$m->set('environment', $data['environment'] ?? static::ENV_HOMOL);
		$m->set('debug_mode', $data['debug_mode'] ?? null);
		$m->set('client_id', $data['client_id'] ?? null);
		$m->set('client_secret', $data['client_secret'] ?? null);
		$m->set('credential', $data['credential'] ?? []);
		$m->set('certificate', $data['certificate'] ?? []);

		return $m;
	}
}
