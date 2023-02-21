<?php

namespace Piggly\ApiClient;

/**
 * API endpoint associated to the wrapper.
 *
 * @since 1.0.9
 * @category API
 * @package Piggly\ApiClient
 * @subpackage Piggly\ApiClient
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 */
abstract class Endpoint
{
	/**
	 * Endpoint request object.
	 *
	 * @var Request
	 * @since 1.0.9
	 */
	protected $_request;

	/**
	 * API wrapper.
	 *
	 * @var Wrapper
	 * @since 1.0.9
	 */
	protected $_api;

	/**
	 * Constructor with $api.
	 *
	 * @param Wrapper $api
	 * @since 1.0.9
	 * @return self
	 */
	public function __construct(Wrapper $api)
	{
		$this->_api = $api;
		$this->_request = $api->getApp()->createEnvironment()->prepare($api->getConfig()->clone(), $api->getApp());
	}

	/**
	 * Get current request client.
	 *
	 * @since 1.0.9
	 * @return Request
	 */
	public function request(): Request
	{
		return $this->_request;
	}
}
