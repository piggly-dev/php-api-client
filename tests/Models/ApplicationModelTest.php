<?php
namespace Piggly\Tests\ApiClient\Models;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Piggly\ApiClient\Configuration;
use Piggly\ApiClient\Interfaces\EnvInterface;
use Piggly\ApiClient\Models\ApplicationModel;
use Piggly\ApiClient\Models\CredentialModel;
use Piggly\ApiClient\Request;
use RuntimeException;

class ProdEnv implements EnvInterface {
	/**
	 * Init enviroment at client configuration.
	 *
	 * @param Configuration $client
	 * @param ApplicationModel $app
	 * @since 0.1.0
	 * @return void
	 */
	public function init(Configuration $client, ApplicationModel $app) {
		// do nothing
	}

	/**
	 * Do an OAuth connection to get the access token.
	 * Must fill application model with credential returned
	 * and return the credential model created.
	 *
	 * @param Configuration $client
	 * @param ApplicationModel $app
	 * @since 0.1.0
	 * @return CredentialModel
	 */
	public function token(Configuration $client, ApplicationModel $app): CredentialModel {
		if ( $app->isAccessTokenValid() ) {
			return $app->get('credential');
		}

		$app->set('credential', [			
			'token_type' => 'Bearer',
			'access_token' => 'jwt',
			'scope' => ['read', 'write'],
			'consented_on' => 1662001200,
			'expires_on' => 1662001200+300,
			'expires_in' => 300,
		]);

		return $app->get('credential');
	}

	/**
	 * Prepare request authenticated requests, filling its headers,
	 * access token, setting host, and anything else.
	 * Must return request created.
	 *
	 * @param Configuration $client
	 * @param ApplicationModel $app
	 * @since 0.1.0
	 * @return Request
	 */
	public function prepare(Configuration $client, ApplicationModel $app): Request {
		return new Request($client);
	}
}

class MainApp extends ApplicationModel {

	/**
	 * Must return the enviroment object according to
	 * the current enviroment.
	 *
	 * @since 1.0.8
	 * @return EnvInterface
	 */
	public function createEnviroment(): EnvInterface {
		if ( $this->get('env', static::ENV_PRODUCTION) ) {
			return new ProdEnv();
		}

		throw new RuntimeException('Invalid enviroment');
	}
}

/**
 * @coversDefaultClass \Piggly\ApiClient\Models\ApplicationModel
 */
class ApplicationModelTest extends TestCase
{
	/** @test Valid mutation. */
	public function assertIfCanMutateEnv ()
	{
		$m = new MainApp();
		$m->set('environment', MainApp::ENV_PRODUCTION);

		$this->assertSame($m->get('environment'), MainApp::ENV_PRODUCTION);
	}

	/** @test Valid mutation. */
	public function throwExceptionWhenSetAnInvalidEnv ()
	{
		$this->expectException(RuntimeException::class);

		$m = new MainApp();
		$m->set('environment', false);
	}

	/** @test Valid mutation. */
	public function assertIfCanMutateDebugMode ()
	{
		$m = new MainApp();

		$this->assertSame($m->set('debug_mode', true)->get('debug_mode'), true);
		$this->assertSame($m->set('debug_mode', false)->get('debug_mode'), false);
		$this->assertSame($m->set('debug_mode', '1')->get('debug_mode'), true);
		$this->assertSame($m->set('debug_mode', '0')->get('debug_mode'), false);
	}

	/** @test Valid mutation. */
	public function assertIfCanMutateCredential ()
	{
		$m = new MainApp();
		$c = new CredentialModel();

		$this->assertSame($m->set('credential', $c)->get('credential'), $c);

		$this->assertSame($m->set('credential', [			
			'token_type' => 'Bearer',
			'access_token' => 'jwt',
			'scope' => ['read', 'write'],
			'consented_on' => 1662001200,
			'expires_on' => 1662001200+300,
			'expires_in' => 300,
		])->get('credential')->export(), [			
			'token_type' => 'Bearer',
			'access_token' => 'jwt',
			'scope' => ['read', 'write'],
			'timezone' => 'UTC',
			'consented_on' => 1662001200,
			'expires_on' => 1662001200+300,
			'expires_in' => 300,
		]);
	}

	/** @test Valid mutation. */
	public function throwExceptionWhenSetAnInvalidCredential ()
	{
		$this->expectException(RuntimeException::class);

		$m = new MainApp();
		$m->set('credential', false);
	}

	/** @test Valid mutation. */
	public function assertIfIsAccessTokenValid ()
	{
		$c = CredentialModel::import([			
			'token_type' => 'Bearer',
			'access_token' => 'jwt',
			'scope' => ['read', 'write'],
			'consented_on' => 1662001200,
			'expires_on' => '2099-09-01 00:00:00'
		]);
		

		$m = new MainApp();
		
		// empty credential
		$this->assertFalse($m->isAccessTokenValid());

		$m->set('credential', $c);

		// with access token and with expiration
		$this->assertTrue($m->isAccessTokenValid());

		// with no access token
		$m->get('credential')->set('access_token', null);

		$this->assertFalse($m->isAccessTokenValid());

		// with access token but expired
		$m->get('credential')->set('access_token', 'jwt')->set('expires_on', '2022-09-01 00:00:00');

		$this->assertFalse($m->isAccessTokenValid());
	}

	/** @test Invalid mutation. */
	public function throwExceptionWhenSetAnInvalidScope ()
	{
		$this->expectException(RuntimeException::class);

		$m = new CredentialModel();
		$m->set('scope', false);
	}
	
	/** @test Valid mutation. */
	public function assertIfIsExported ()
	{
		$m = new MainApp();

		$this->assertSame(
			$m->export(), 
			[			
				'environment' => MainApp::ENV_HOMOL,
				'debug_mode' => false,
				'client_id' => null,
				'client_secret' => null,
				'credential' => [],
				'certificate' => [],
			]
		);
	}
	
	/** @test Valid mutation. */
	public function assertIfIsCreated ()
	{
		$c = CredentialModel::import([			
			'token_type' => 'Bearer',
			'access_token' => 'jwt',
			'scope' => ['read', 'write'],
			'consented_on' => 1662001200,
			'expires_on' => '2099-09-01 00:00:00'
		]);
		

		$m = MainApp::import([
			'environment' => MainApp::ENV_PRODUCTION,
			'debug_mode' => true,
			'client_id' => 'client_id',
			'client_secret' => 'client_secret',
			'credential' => $c->export(),
			'certificate' => [],
		]);
		
		$this->assertSame(
			$m->export(), 
			[
				'environment' => MainApp::ENV_PRODUCTION,
				'debug_mode' => true,
				'client_id' => 'client_id',
				'client_secret' => 'client_secret',
				'credential' => $c->export(),
				'certificate' => [],
			]
		);
	}
}