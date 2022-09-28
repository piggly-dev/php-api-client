<?php
namespace Piggly\Tests\ApiClient\Models;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Piggly\ApiClient\Models\CredentialModel;
use RuntimeException;

/**
 * @coversDefaultClass \Piggly\ApiClient\Models\CredentialModel
 */
class CredentialModelTest extends TestCase
{
	/** @test Valid mutation. */
	public function assertIfCanMutateScopeAsString ()
	{
		$m = new CredentialModel();
		$m->set('scope', 'read write');

		$this->assertSame($m->get('scope'), ['read', 'write']);
	}

	/** @test Valid mutation. */
	public function assertIfCanMutateScopeAsArray ()
	{
		$m = new CredentialModel();
		$m->set('scope', ['read', 'write']);

		$this->assertSame($m->get('scope'), ['read', 'write']);
	}

	/** @test Invalid mutation. */
	public function throwExceptionWhenSetAnInvalidScope ()
	{
		$this->expectException(RuntimeException::class);

		$m = new CredentialModel();
		$m->set('scope', false);
	}
	
	/** @test Valid mutation. */
	public function assertIfCanMutateDateAsObject ()
	{
		$d = new DateTimeImmutable('now');
		$m = new CredentialModel();
		$m->set('consented_on', $d);
		$m->set('expires_on', $d);

		$this->assertSame($m->get('consented_on'), $d);
		$this->assertSame($m->get('expires_on'), $d);
	}

	/** @test Valid mutation. */
	public function assertIfCanMutateDateAsString ()
	{
		$m = new CredentialModel();
		$m->set('consented_on', '2022-09-01 00:00:00');
		$m->set('expires_on', '2022-09-01 00:00:00');

		$this->assertInstanceOf(DateTimeImmutable::class, $m->get('consented_on'));
		$this->assertInstanceOf(DateTimeImmutable::class, $m->get('expires_on'));

		$this->assertSame($m->get('consented_on')->format('Y-m-d H:i:s'), '2022-09-01 00:00:00');
		$this->assertSame($m->get('expires_on')->format('Y-m-d H:i:s'), '2022-09-01 00:00:00');
	}

	/** @test Valid mutation. */
	public function assertIfCanMutateDateAsInteger ()
	{
		$m = new CredentialModel();
		$m->set('consented_on', 1662001200); // 2022-09-01 03:00:00 UTC
		$m->set('expires_on', 1662001200); // 2022-09-01 03:00:00 UTC

		$this->assertInstanceOf(DateTimeImmutable::class, $m->get('consented_on'));
		$this->assertInstanceOf(DateTimeImmutable::class, $m->get('expires_on'));

		$this->assertSame($m->get('consented_on')->format('Y-m-d H:i:s'), '2022-09-01 03:00:00');
		$this->assertSame($m->get('expires_on')->format('Y-m-d H:i:s'), '2022-09-01 03:00:00');
	}

	/** @test Invalid mutation. */
	public function throwExceptionWhenSetAnInvalidDate ()
	{
		$this->expectException(RuntimeException::class);

		$m = new CredentialModel();
		$m->set('consented_on', false);
		$m->set('expires_on', false);
	}
	
	/** @test Valid mutation. */
	public function assertIfCanMutateExpiresIn ()
	{
		$m = new CredentialModel();
		$m->set('consented_on', '2022-09-01 00:00:00');
		$m->set('expires_in', 300);

		$this->assertTrue($m->has('expires_on'));
		$this->assertSame($m->get('expires_on')->format('Y-m-d H:i:s'), '2022-09-01 00:05:00');
		$this->assertSame($m->get('expires_in'), 300);
	}
	
	/** @test Valid mutation. */
	public function assertIfCanMutateExpiresInAndKeepExpiresOn ()
	{
		$m = new CredentialModel();
		$m->set('consented_on', '2022-09-01 00:00:00');
		$m->set('expires_on', '2022-09-01 00:10:00');
		$m->set('expires_in', 300);

		$this->assertSame($m->get('expires_on')->format('Y-m-d H:i:s'), '2022-09-01 00:10:00');
		$this->assertSame($m->get('expires_in'), 300);
	}
	
	/** @test Valid mutation. */
	public function assertIfIsExpired ()
	{
		$m = new CredentialModel();
		$m->set('expires_on', '2022-09-01 00:00:00');

		$this->assertTrue($m->isExpired());
	}
	
	/** @test Valid mutation. */
	public function assertIfIsNotExpired ()
	{
		$m = new CredentialModel();
		$m->set('expires_on', '2099-09-01 00:00:00');

		$this->assertFalse($m->isExpired());
		
		$m = new CredentialModel();
		// empty expires on
		$this->assertFalse($m->isExpired());
	}
	
	/** @test Valid mutation. */
	public function assertIfIsExported ()
	{
		$m = new CredentialModel();

		$this->assertSame(
			$m->export(), 
			[			
				'token_type' => 'Bearer',
				'access_token' => null,
				'scope' => null,
				'timezone' => 'UTC',
				'consented_on' => null,
				'expires_on' => null,
				'expires_in' => null,
			]
		);

		$m->set('access_token', 'jwt');
		$m->set('scope', 'read write');
		$m->set('consented_on', '2022-09-01 03:00:00');
		$m->set('expires_in', 300);

		$this->assertSame(
			$m->export(), 
			[			
				'token_type' => 'Bearer',
				'access_token' => 'jwt',
				'scope' => ['read', 'write'],
				'timezone' => 'UTC',
				'consented_on' => 1662001200,
				'expires_on' => 1662001200+300,
				'expires_in' => 300,
			]
		);
	}
	
	/** @test Valid mutation. */
	public function assertIfIsCreated ()
	{
		$m = CredentialModel::import([			
			'token_type' => 'Bearer',
			'access_token' => 'jwt',
			'scope' => ['read', 'write'],
			'consented_on' => 1662001200,
			'expires_on' => 1662001200+300,
			'expires_in' => 300,
		]);
		
		$this->assertSame(
			$m->export(), 
			[			
				'token_type' => 'Bearer',
				'access_token' => 'jwt',
				'scope' => ['read', 'write'],
				'timezone' => 'UTC',
				'consented_on' => 1662001200,
				'expires_on' => 1662001200+300,
				'expires_in' => 300,
			]
		);
	}
}