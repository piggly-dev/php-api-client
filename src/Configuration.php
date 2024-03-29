<?php

namespace Piggly\ApiClient;

use InvalidArgumentException;
use Monolog\Logger;
use Piggly\ApiClient\Supports\HeaderBag;
use RuntimeException;

/**
 * The master configuration to ApiClient object.
 * This will prepare core settings to connect with
 * api HTTP host.
 *
 * @since 1.0.0
 * @since 2.0.0 Cannot load data from .env file.
 * @category Class
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
class Configuration
{
	/**
	 * Default timeout to HTTP connection.
	 * 0 means there is not timeout.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	public const DEFAULT_TIMEOUT = 0;

	/**
	 * Default configuration static object.
	 *
	 * @var Configuration
	 * @since 1.0.0
	 */
	private static $_default = null;

	/**
	 * All settings.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $_settings;

	/**
	 * Constructor setting all settings data.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct()
	{
		$this->_settings = [
			'api.keys' => [],
			'http.headers' => new HeaderBag(),
			'custom' => [],
			'curl' => []
		];
	}

	/**
	 * Set custom configuration by $option.
	 *
	 * @param int $option
	 * @param mixed $value
	 * @since 1.0.6
	 * @return Configuration
	 */
	public function curlModifier(int $option, $value)
	{
		$this->_settings['curl'][$option] = $value;
		return $this;
	}

	/**
	 * Get all curl modifiers.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @since 1.0.0
	 * @return mixed
	 */
	public function getCurlModifiers(): ?array
	{
		return $this->_settings['curl'] ?? [];
	}

	/**
	 * Set custom configuration by $key.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function custom(string $key, $value)
	{
		$this->_settings['custom'][$key] = $value;
		return $this;
	}

	/**
	 * Get custom configuration by $key.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @since 1.0.0
	 * @since 1.0.6 Return mixed values.
	 * @return mixed
	 */
	public function getCustom(string $key, $default = null)
	{
		return $this->_settings['custom'][$key] ?? $default;
	}

	/**
	 * Set the API key to connect.
	 *
	 * @param string $identifier ID to key. (authentication scheme)
	 * @param string $key API key.
	 * @param string $prefix API key prefix. (e.g. Bearer)
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function apiKey(string $identifier, string $key, string $prefix = null)
	{
		$this->_settings['api.keys'][$identifier] = [$prefix, $key];
		return $this;
	}

	/**
	 * Get the API key by $identifier.
	 * It will return an array with [$prefix, $key] or [null, null].
	 *
	 * @param string $identifier ID to key. (authentication scheme)
	 * @since 1.0.0
	 * @return array
	 */
	public function getApiKey(string $identifier): array
	{
		return $this->_settings['api.keys'][$identifier] ?? [null, null];
	}

	/**
	 * Set the access token to OAuth scheme.
	 *
	 * @param string $accessToken
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function accessToken(string $accessToken)
	{
		$this->_settings['oauth.accesstoken'] = $accessToken;
		return $this;
	}

	/**
	 * Get the access token to OAuth scheme.
	 *
	 * @since 1.0.0
	 * @return string|null
	 */
	public function getAccessToken(): ?string
	{
		return $this->_settings['oauth.accesstoken'] ?? null;
	}

	/**
	 * Set username to HTTP Basic Authentication method.
	 *
	 * @param string $password
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function username(string $username)
	{
		$this->_settings['http.basic.user'] = $username;
		return $this;
	}

	/**
	 * Get the username to HTTP Basic Authentication method.
	 *
	 * @since 1.0.0
	 * @return string|null
	 */
	public function getUsername(): ?string
	{
		return $this->_settings['http.basic.user'] ?? null;
	}

	/**
	 * Set password to HTTP Basic Authentication method.
	 *
	 * @param string $password
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function password(string $password)
	{
		$this->_settings['http.basic.password'] = $password;
		return $this;
	}

	/**
	 * Get the password to HTTP Basic Authentication method.
	 *
	 * @since 1.0.0
	 * @return string|null
	 */
	public function getPassword(): ?string
	{
		return $this->_settings['http.basic.password'] ?? null;
	}

	/**
	 * Access HTTP headers and manipulate them.
	 *
	 * @since 1.0.0
	 * @return HeaderBag
	 */
	public function headers(): HeaderBag
	{
		return $this->_settings['http.headers'];
	}

	/**
	 * Clone HTTP headers and manipulate them.
	 *
	 * @since 1.0.0
	 * @return HeaderBag
	 */
	public function cloneHeaders(): HeaderBag
	{
		return (clone $this->_settings['http.headers']);
	}

	/**
	 * Set the HTTP host.
	 *
	 * @since 1.0.0
	 * @param boolean $debug
	 * @return Configuration
	 */
	public function host(string $host)
	{
		$this->_settings['http.host'] = trim($host, '/');
		return $this;
	}

	/**
	 * Get the HTTP host.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function getHost(): ?string
	{
		return $this->_settings['http.host'] ?? null;
	}

	/**
	 * Set the HTTP User Agent.
	 *
	 * @since 1.0.0
	 * @param boolean $debug
	 * @return Configuration
	 */
	public function userAgent(string $userAgent)
	{
		$this->_settings['http.useragent'] = $userAgent;
		return $this;
	}

	/**
	 * Get the HTTP User Agent.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function getUserAgent(): ?string
	{
		return $this->_settings['http.useragent'] ?? null;
	}

	/**
	 * Set the HTTP timeout in seconds.
	 *
	 * @since 1.0.0
	 * @param boolean $seconds
	 * @return Configuration
	 * @throws InvalidArgumentException
	 */
	public function timeout(int $seconds)
	{
		if ($seconds < 0) {
			throw new InvalidArgumentException('Timeout must be a non-negative integer.');
		}

		$this->_settings['curl.timeout'] = $seconds;
		return $this;
	}

	/**
	 * Get the HTTP timeout in seconds.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function getTimeout(): int
	{
		return $this->_settings['curl.timeout'] ?? self::DEFAULT_TIMEOUT;
	}

	/**
	 * Set the HTTP connection timeout in seconds.
	 *
	 * @param boolean $seconds
	 * @since 1.0.0
	 * @return Configuration
	 * @throws InvalidArgumentException
	 */
	public function connectionTimeout(int $seconds)
	{
		if ($seconds < 0) {
			throw new InvalidArgumentException('Connection timeout must be a non-negative integer.');
		}

		$this->_settings['curl.connection.timeout'] = $seconds;
		return $this;
	}

	/**
	 * Get the HTTP connection timeout in seconds.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function getConnectionTimeout(): int
	{
		return $this->_settings['curl.connection.timeout'] ?? self::DEFAULT_TIMEOUT;
	}

	/**
	 * Set the HTTP proxy host.
	 *
	 * @since 1.0.0
	 * @param string $host
	 * @return Configuration
	 */
	public function proxyHost(string $host)
	{
		$this->_settings['curl.proxy.host'] = $host;
		return $this;
	}

	/**
	 * Get the HTTP proxy host.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function getProxyHost(): ?string
	{
		return $this->_settings['curl.proxy.host'] ?? null;
	}

	/**
	 * Set the HTTP proxy port.
	 *
	 * @param int $port
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function proxyPort(int $port)
	{
		$this->_settings['curl.proxy.port'] = $port;
		return $this;
	}

	/**
	 * Get the HTTP proxy port.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function getProxyPort(): ?int
	{
		return $this->_settings['curl.proxy.port'] ?? null;
	}

	/**
	 * Set the HTTP proxy type.
	 * Curl proxy type, e.g. CURLPROXY_HTTP or CURLPROXY_SOCKS5
	*
	* @see https://secure.php.net/manual/en/function.curl-setopt.php
	 * @param int $type
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function proxyType(int $type)
	{
		$this->_settings['curl.proxy.type'] = $type;
		return $this;
	}

	/**
	 * Get the HTTP proxy type.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function getProxyType(): ?int
	{
		return $this->_settings['curl.proxy.type'] ?? null;
	}

	/**
	 * Set the HTTP proxy user.
	 *
	 * @param string $user
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function proxyUser(string $user)
	{
		$this->_settings['curl.proxy.user'] = $user;
		return $this;
	}

	/**
	 * Get the HTTP proxy user.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function getProxyUser(): ?string
	{
		return $this->_settings['curl.proxy.user'] ?? null;
	}

	/**
	 * Set the HTTP proxy password.
	 *
	 * @param string $password
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function proxyPassword(string $password)
	{
		$this->_settings['curl.proxy.password'] = $password;
		return $this;
	}

	/**
	 * Get the HTTP proxy password.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function getProxyPassword(): ?string
	{
		return $this->_settings['curl.proxy.password'] ?? null;
	}

	/**
	 * Set the debug mode.
	 *
	 * @param boolean $debug
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function debug(bool $debug)
	{
		$this->_settings['debug'] = $debug;
		return $this;
	}

	/**
	 * Set up the application logger.
	 *
	 * @param Logger $logger
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function logger(Logger $logger)
	{
		$this->_settings['logger'] = $logger;
		return $this;
	}

	/**
	 * Get current environment related to configuration.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function getEnv(): string
	{
		return $this->_settings['env'] ?? 'default';
	}

	/**
	 * Get if debug mode is enabled.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function isDebugging(): bool
	{
		return $this->_settings['debug'] ?? false;
	}

	/**
	 * Get if logger is enabled.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function hasLogger(): bool
	{
		return isset($this->_settings['logger']) && !empty($this->_settings['logger']) ? true : false;
	}

	/**
	 * Add a record to current logger.
	 *
	 * @param integer $level
	 * @param string $message
	 * @param array $context
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function log($level, $message, array $context = [])
	{
		if (!$this->hasLogger()) {
			return $this;
		}

		$level = Logger::toMonologLevel($level);

		if ($level === Logger::DEBUG && !$this->isDebugging()) {
			return $this;
		}

		$this->_settings['logger']->addRecord(
			$level,
			\sprintf('%s :: %s', $this->getEnv(), (string)$message),
			$context
		);

		return $this;
	}

	/**
	 * Set if should or not do a SSL verification.
	 * This is useful if host uses a  self-signed
	 * SSL certificate in developer environment.
	 *
	 * @param boolean $verify
	 * @since 1.0.0
	 * @return Configuration
	 */
	public function verifySSL(bool $verify)
	{
		$this->_settings['http.ssl'] = $verify;
		return $this;
	}

	/**
	 * Get if should do a SSL verification.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function shouldVerifySSL(): bool
	{
		return $this->_settings['http.ssl'] ?? false;
	}

	/**
	 * Clone current object.
	 *
	 * @since 1.0.9
	 * @return Configuration
	 */
	public function clone(): Configuration
	{
		$conf = new Configuration();
		$conf->_settings = $this->_settings;

		return $conf;
	}

	/**
	 * Method not available anymore.
	 *
	 * @since 1.0.0
	 * @deprecated 2.0.0
	 * @return Configuration
	 */
	public function env(string $path, string $env = null)
	{
		throw new RuntimeException('Method not available anymore.');
	}

	/**
	 * Set static default object configuration.
	 * One for all instances.
	 *
	 * @param Configuration $config
	 * @since 1.0.0
	 * @return Configuration Default configuration
	 */
	public static function default(Configuration $config): Configuration
	{
		self::$_default = $config;
		return $config;
	}

	/**
	 * Get static default object configuration.
	 * One for all instances.
	 *
	 * @since 1.0.0
	 * @return Configuration
	 */
	public static function getDefault(): Configuration
	{
		if (is_null(self::$_default)) {
			self::$_default = new Configuration();
		}

		return self::$_default;
	}
}
