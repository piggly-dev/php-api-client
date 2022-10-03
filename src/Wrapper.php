<?php
namespace Piggly\ApiClient;

use Monolog\Logger;
use Piggly\ApiClient\Models\ApplicationModel;
use RuntimeException;

/**
 * API wrapper. Here must have all endpoints to it.
 * Append to this class methods for each endpoints.
 * 
 * If API has the /movies endpoint, create moviesApi()
 * returning the endpoint object.
 * 
 * @since 1.0.9
 * @category API
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
abstract class Wrapper
{
	/**
	 * Application settings.
	 *
	 * @since 1.0.9
	 * @var ApplicationModel
	 */
	protected $_app;

	/**
	 * Client configuration.
	 *
	 * @since 1.0.9
	 * @var Configuration
	 */
	protected $_client;
	
	/**
	 * Creates the API wrapper
	 * to handle all API endpoints.
	 *
	 * @param ApplicationModel $app
	 * @param Logger|null $logger
	 * @since 1.0.9
	 * @return void
	 * @throws RuntimeException
	 */
	public function __construct(
		ApplicationModel $app,
		Logger $logger = null
	) {
		$this->_app = $app;
		$this->_client = new Configuration();

		if ($app->isDebugging()) {
			// Enable debug mode on client configuration.
			$this->_client->debug(true);
		}

		if (!empty($logger)) {
			// Set logger to client configuration
			$this->_client->logger($logger);
		}

		// Init application enviroment, mutation configuration and application
		$app->createEnvironment()->init($this->_client, $this->_app);
	}

	/**
	 * Get an endpoint by name.
	 *
	 * @param string $name
	 * @since 1.0.9
	 * @return Endpoint
	 */
	public function endpoint (string $name) {
		$endpoints = static::endpointClasses();

		if (isset($endpoints[$name]) ) {
			$cls = $endpoints[$name];
			return new $cls($this);
		}

		throw new RuntimeException('Endpoint not implemented. Its class must exists on static::endpointClasses method.');
	}

	/**
	 * Get application.
	 *
	 * @since 1.0.9
	 * @return ApplicationModel
	 */
	public function getApp(): ApplicationModel
	{
		return $this->_app;
	}

	/**
	 * Get client configuration.
	 *
	 * @since 1.0.9
	 * @return Configuration
	 */
	public function getConfig(): Configuration
	{
		return $this->_client;
	}

	/**
	 * Get all endpoint classes name.
	 * Must return an array with endpoint name 
	 * associated with its class name. eg:
	 * 
	 * [ 'movies' => \Api\Movies::class ];
	 * 
	 * @since 1.0.9
	 * @return array
	 */
	abstract public static function endpointClasses () : array;
}