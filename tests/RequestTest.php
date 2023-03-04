<?php

namespace Piggly\Tests\ApiClient\Supports;

use PHPUnit\Framework\TestCase;
use Piggly\ApiClient\Configuration;
use Piggly\ApiClient\Request;
use ReflectionClass;

/**
 * @coversDefaultClass \Piggly\ApiClient\Request
 */
class RequestTest extends TestCase
{
	/**
	 * Configuration.
	 *
	 * @var Configuration
	 */
	protected $config;

	/**
	 * Setup configuration.
	 * It will use a fake API for testing.
	 *
	 * @see https://jsonplaceholder.typicode.com/
	 * @since 1.0.0
	 * @return void
	 */
	protected function setUp(): void
	{
		$this->config = new Configuration();

		$this
			->config
			->host('https://jsonplaceholder.typicode.com/')
			->userAgent('Testing/1.0');
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanDoGETRequest()
	{
		$request = new Request($this->config);
		$response = $request->get('/posts/1')->call();

		$this->assertSame(
			1,
			$response->getBody()['id']
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanDoPATCHRequest()
	{
		$request = new Request($this->config);
		$request->getHeaders()->add('Content-Type', 'application/json; charset=UTF-8');

		$_body = [
			'title' => 'foo'
		];

		$response = $request->put('/posts/1', $_body)->call();

		$this->assertSame(
			'foo',
			$response->getBody()['title']
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanDoPOSTRequest()
	{
		$request = new Request($this->config);
		$request->getHeaders()->add('Content-Type', 'application/json; charset=UTF-8');

		$_body = [
			'userId' => 1,
			'title' => 'foo',
			'body' => 'bar'
		];

		$response = $request->post('/posts', $_body)->call();

		$this->assertSame(
			[
				'userId' => 1,
				'title' => 'foo',
				'body' => 'bar',
				'id' => 101
			],
			$response->getBody()
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanDoPUTRequest()
	{
		$request = new Request($this->config);
		$request->getHeaders()->add('Content-Type', 'application/json; charset=UTF-8');

		$_body = [
			'id' => 1,
			'userId' => 1,
			'title' => 'foo',
			'body' => 'bar'
		];

		$response = $request->put('/posts/1', $_body)->call();

		$this->assertSame(
			[
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'body' => 'bar'
			],
			$response->getBody()
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanPreparePOSTDataAsURLEncoded()
	{
		$request = new Request($this->config);
		$request->getHeaders()->add('Content-Type', 'application/x-www-form-urlencoded');

		$_body = [
			'id' => 1,
			'userId' => 1,
			'title' => 'foo',
			'body' => 'bar'
		];

		$this->assertSame(
			\http_build_query($_body),
			$this->_invoke($request, 'preparePostData', $request->getHeaders(), $_body)
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanPreparePOSTDataAsJson()
	{
		$request = new Request($this->config);
		$request->getHeaders()->add('Content-Type', 'application/json');

		$_body = [
			'id' => 1,
			'userId' => 1,
			'title' => 'foo',
			'body' => 'bar'
		];

		$this->assertSame(
			\json_encode($_body),
			$this->_invoke($request, 'preparePostData', $request->getHeaders(), $_body)
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanSetBasicAuthorizationHeader()
	{
		$this->config->username('foo')->password('bar');

		$request = new Request($this->config);
		$request->basicAuth();

		$this->assertSame(
			['Basic '.\base64_encode('foo:bar')],
			$request->getHeaders()->get('authorization')
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanSetAuthorizationHeader()
	{
		$this->config->apiKey('main', 'foo', 'Bearer');

		$request = new Request($this->config);
		$request->authorization('main');

		$this->assertSame(
			['Bearer foo'],
			$request->getHeaders()->get('authorization')
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanPrepareUri()
	{
		$request = new Request($this->config);
		$request->get('/posts/1');

		$this->assertSame(
			'https://jsonplaceholder.typicode.com/posts/1',
			$this->_invoke($request, 'getUri')
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanPrepareUriReplacingParams()
	{
		$request = new Request($this->config);
		$request->get('/posts/{id}')->params(['id' => 1]);

		$this->assertSame(
			'https://jsonplaceholder.typicode.com/posts/1',
			$this->_invoke($request, 'getUri')
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanPrepareUriWithQueryParameters()
	{
		$request = new Request($this->config);
		$request->get('/posts/1')->query(['foo' => 'bar']);

		$this->assertSame(
			'https://jsonplaceholder.typicode.com/posts/1?foo=bar',
			$this->_invoke($request, 'getUri')
		);
	}

	/**
	 * For testing protected methods required.
	 *
	 * @param Request $request
	 * @param string $method
	 * @param array ...$args
	 * @return void
	 */
	private function _invoke(Request $request, string $method, ...$args)
	{
		$class = new ReflectionClass($request);
		$method = $class->getMethod($method);

		$method->setAccessible(true);
		return $method->invokeArgs($request, $args);
	}
}
