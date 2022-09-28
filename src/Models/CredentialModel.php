<?php
namespace Piggly\ApiClient\Models;

use DateTimeImmutable;
use RuntimeException;

/**
 * Model for a credential token. The expected fields are:
 * 
 * scope
 * access_token
 * token_type
 * consented_on
 * expires_on
 * expires_in
 * 
 * @since 1.0.8
 * @category Interfaces
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient\Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class CredentialModel extends AbstractModel
{
	/**
	 * Bearer token type.
	 * 
	 * @var string
	 * @since 1.0.8
	 */
	const TOKEN_TYPE_BEARER = 'Bearer';

	/**
	 * Mutate scope string to an array.
	 *
	 * @param string|array $value
	 * @since 1.0.8
	 * @return array
	 * @throws RuntimeException
	 */
	protected function mutate_scope($value)
	{
		if ( \is_string($value)) {
			return \explode(' ', $value);
		} elseif (\is_array($value)) {
			return $value;
		}

		throw new RuntimeException('Invalid scope value.');
	}

	/**
	 * Mutate consented on date to DateTimeImmutable.
	 *
	 * @param string|integer|DateTimeImmutable $value
	 * @since 1.0.8
	 * @return DateTimeImmutable
	 */
	protected function mutate_consented_on ($value) {
		if ($value instanceof DateTimeImmutable) {
			return $value;
		} elseif (\is_string($value)) {
			return new DateTimeImmutable($value);
		} elseif (\is_integer($value)) {
			return new DateTimeImmutable('@'.$value);
		}

		throw new RuntimeException('Invalid consented_on date value.');
	}

	/**
	 * Mutate expires on date to DateTimeImmutable.
	 *
	 * @param string|integer|DateTimeImmutable $value
	 * @since 1.0.8
	 * @return DateTimeImmutable
	 */
	protected function mutate_expires_on ($value) {
		if ($value instanceof DateTimeImmutable) {
			return $value;
		} elseif (\is_string($value)) {
			return new DateTimeImmutable($value);
		} elseif (\is_integer($value)) {
			return new DateTimeImmutable('@'.$value);
		}

		throw new RuntimeException('Invalid expires_on date value.');
	}

	/**
	 * Mutate expires on date to DateTimeImmutable.
	 *
	 * @param integer $value
	 * @since 1.0.8
	 * @return integer
	 */
	protected function mutate_expires_in ($value) {
		if (!empty($this->_fields['consented_on']) && empty($this->_fields['expires_on'])) {
			$this->_fields['expires_on'] = new DateTimeImmutable('@'.\strval($this->_fields['consented_on']->getTimestamp())+$value);
		}

		return \intval($value);
	}

	/**
	 * Return if credential has expired.
	 *
	 * @since 1.0.8
	 * @return boolean
	 */
	public function isExpired () : bool {
		if ( empty($this->_fields['expires_on']) ) {
			return true;
		}

		$now = new DateTimeImmutable('now', $this->_fields['expires_on']->getTimezone());
		return $this->_fields['expires_on'] <= $now;
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
			'token_type' => $this->get('token_type'),
			'access_token' => $this->get('access_token'),
			'scope' => $this->get('scope'),
			'consented_on' => $this->has('consented_on') ? $this->get('consented_on')->getTimestamp() : null,
			'expires_on' => $this->has('expires_on') ? $this->get('expires_on')->getTimestamp() : null,
			'expires_in' => $this->get('expires_in'),
		];
	}

	/**
	 * Create a new credential model with data.
	 *
	 * @param array $data
	 * @since 1.0.8
	 * @return self
	 */
	public static function import(array $data)
	{
		$m = new CredentialModel();

		$m->set('token_type', $data['token_type'] ?? static::TOKEN_TYPE_BEARER);
		$m->set('access_token', $data['access_token'] ?? null);
		$m->set('scope', $data['scope'] ?? null);

		if ( !empty($data['consented_on']) ) $m->set('consented_on', $data['consented_on']);
		if ( !empty($data['expired_on']) ) $m->set('expired_on', $data['expired_on']);
		if ( !empty($data['expires_in']) ) $m->set('expires_in', $data['expires_in']);

		return $m;
	}
}
