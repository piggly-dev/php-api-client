<?php
namespace Piggly\Tests\ApiClient\Supports;

use PHPUnit\Framework\TestCase;
use Piggly\ApiClient\Configuration;

/**
 * @coversDefaultClass \Piggly\ApiClient\Configuration
 */
class ConfigurationTest extends TestCase
{
	/** @test Expecting positive assertion. */
	public function assertIfCanReadEnvironment ()
	{
		$config = new Configuration();
		
		$config->env(
			__DIR__.'/../',
			'example'
		);

		$this->assertSame(
			[
				'username' => 'username',
				'password' => 'password',
				'debug' => true,
				'host' => 'http://localhost',
				'userAgent' => 'MyServer/1.0',
				'timeout' => 60,
				'connectionTimeout' => 60,
				'proxyHost' => 'proxy://localhost',
				'proxyPort' => 5050,
				'proxyUser' => 'proxyusername',
				'proxyPassword' => 'proxypassword'
			],
			[
				'username' => $config->getUsername(),
				'password' => $config->getPassword(),
				'debug' => $config->isDebugging(),
				'host' => $config->getHost(),
				'userAgent' => $config->getUserAgent(),
				'timeout' => $config->getTimeout(),
				'connectionTimeout' => $config->getConnectionTimeout(),
				'proxyHost' => $config->getProxyHost(),
				'proxyPort' => $config->getProxyPort(),
				'proxyUser' => $config->getProxyUser(),
				'proxyPassword' => $config->getProxyPassword()
			]
		);
	}
}