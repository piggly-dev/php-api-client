<?php
namespace Piggly\ApiClient\Models;

use Piggly\ApiClient\Interfaces\EnvInterface;
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
 * @since 1.0.9
 * @category Interfaces
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
abstract class ApplicationModel extends AbstractModel
{
	/**
	 * Test Environment.
	 *
	 * @since 1.0.9
	 * @var string
	 */
	public const ENV_TEST = 'test';

	/**
	 * Sandbox/homologation Environment.
	 *
	 * @since 1.0.9
	 * @var string
	 */
	public const ENV_HOMOL = 'homol';

	/**
	 * Production Environment.
	 *
	 * @since 1.0.9
	 * @var string
	 */
	public const ENV_PRODUCTION = 'prod';

	/**
	 * Mutate env validating if has valid value.
	 *
	 * @param string|array $value
	 * @since 1.0.9
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
	 * @since 1.0.9
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
	 * @since 1.0.9
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
	 * @since 1.0.9
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
	 * @since 1.0.9
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
	 * Return if application is debugging.
	 *
	 * @since 1.0.9
	 * @return boolean
	 */
	public function isDebugging ():bool {
		return $this->get('debug_mode', false);
	}

	/**
	 * Check if current env is equal to $expected.
	 *
	 * @param string $expected
	 * @since 1.0.9
	 * @return boolean
	 */
	public function isEnv(string $expected): bool
	{
		return $this->get('environment') === $expected;
	}

	/**
	 * Must return the Environment object according to
	 * the current Environment.
	 *
	 * @since 1.0.9
	 * @return EnvInterface
	 */
	abstract public function createEnvironment(): EnvInterface;

	/**
	 * Export object data to an array.
	 *
	 * @since 1.0.9
	 * @return array
	 */
	public function export(): array
	{
		return [
			'environment' => $this->get('environment', static::ENV_HOMOL),
			'debug_mode' => $this->get('debug_mode', false),
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
	 * @since 1.0.9
	 * @return self
	 */
	public static function import(array $data)
	{
		$m = new static(); 

		$m->set('environment', $data['environment'] ?? static::ENV_HOMOL);
		$m->set('debug_mode', $data['debug_mode'] ?? false);
		$m->set('client_id', $data['client_id'] ?? null);
		$m->set('client_secret', $data['client_secret'] ?? null);
		$m->set('credential', $data['credential'] ?? []);
		$m->set('certificate', $data['certificate'] ?? []);

		return $m;
	}
}
