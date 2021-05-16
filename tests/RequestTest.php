<?php
namespace Piggly\Tests\ApiClient\Supports;

use PHPUnit\Framework\TestCase;
use Piggly\ApiClient\Configuration;
use Piggly\ApiClient\Request;

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
	protected  function setUp () : void
	{
		$this->config = new Configuration();

		$this
			->config
			->host('https://jsonplaceholder.typicode.com/')
			->userAgent('Testing/1.0');
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanDoGETRequest ()
	{
		$request = new Request($this->config);
		list($body, $code, $headers) = $request->get('/posts/1')->call();

		$this->assertSame(
			1,
			$body['id']
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanDoPATCHRequest ()
	{
		$request = new Request($this->config);
		$request->headers()->add('Content-Type', 'application/json; charset=UTF-8');
		
		$_body = [
			'title' => 'foo'
		];

		list($body, $code, $headers) = $request->put('/posts/1', $_body)->call();

		$this->assertSame(
			'foo',
			$body['title']
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanDoPOSTRequest ()
	{
		$request = new Request($this->config);
		$request->headers()->add('Content-Type', 'application/json; charset=UTF-8');
		
		$_body = [
			'userId' => 1,
			'title' => 'foo',
			'body' => 'bar'
		];

		list($body, $code, $headers) = $request->post('/posts', $_body)->call();

		$this->assertSame(
			[
				'userId' => 1,
				'title' => 'foo',
				'body' => 'bar',
				'id' => 101
			],
			$body
		);
	}

	/** @test Expecting posive assertion. */
	public function assertIfCanDoPUTRequest ()
	{
		$request = new Request($this->config);
		$request->headers()->add('Content-Type', 'application/json; charset=UTF-8');
		
		$_body = [
			'id' => 1,
			'userId' => 1,
			'title' => 'foo',
			'body' => 'bar'
		];

		list($body, $code, $headers) = $request->put('/posts/1', $_body)->call();

		$this->assertSame(
			[
				'id' => 1,
				'userId' => 1,
				'title' => 'foo',
				'body' => 'bar'
			],
			$body
		);
	}
}