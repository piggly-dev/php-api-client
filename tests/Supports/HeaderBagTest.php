<?php
namespace Piggly\Tests\ApiClient\Supports;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Piggly\ApiClient\Supports\HeaderBag;

/**
 * @coversDefaultClass \Piggly\ApiClient\Supports\HeaderBag
 */
class HeaderBagTest extends TestCase
{
	/** @test Expecting positive assertion. */
	public function assertIfCanAddRawHeader ()
	{
		$headers = new HeaderBag();
		$headers->raw('Accept: text/html, application/xhtml+xml, application/xml');

		$this->assertSame(
			[ 'Accept: text/html, application/xhtml+xml, application/xml' ],
			$headers->cURL()
		);
	}

	/** @test Expecting positive assertion. */
	public function assertIfCanAddHeaderWithContentString ()
	{
		$headers = new HeaderBag();
		$headers->add('Accept', 'text/html, application/xhtml+xml, application/xml');

		$this->assertSame(
			[ 'text/html', 'application/xhtml+xml', 'application/xml' ],
			$headers->get('Accept')
		);
	}

	/** @test Expecting positive assertion. */
	public function assertIfCanAddHeaderWithContentArray ()
	{
		$headers = new HeaderBag();
		$headers->add('Accept', [ 'text/html', 'application/xhtml+xml', 'application/xml' ]);
		
		$this->assertSame(
			[ 'text/html', 'application/xhtml+xml', 'application/xml' ],
			$headers->get('Accept')
		);
	}

	/** @test Expecting positive assertion. */
	public function assertExceptionIfCannnotAddHeaderWithInvalidContent ()
	{
		$this->expectException(InvalidArgumentException::class);
		$headers = new HeaderBag();
		$headers->add('Accept', 0);
	}

	/** @test Expecting positive assertion. */
	public function assertIfCanAppendHeaderWhichAlreadyExists ()
	{
		$headers = new HeaderBag();
		$headers
			->add('Accept', 'text/html, application/xhtml+xml')
			->append('Accept', 'application/xml');

		$this->assertSame(
			[ 'text/html', 'application/xhtml+xml', 'application/xml' ],
			$headers->get('Accept') 
		);
	}

	/** @test Expecting negative assertion. */
	public function assertIfCanMergeTwoHeaders ()
	{
		$one = new HeaderBag();
		$one->add('Accept', 'text/html, application/xhtml+xml');

		$two = new HeaderBag();
		$two->add('Accept', 'application/xml');

		$this->assertSame(
			[ 'application/xml' ],
			$one->mergeWith($two)->get('Accept')
		);
	}

	/** @test Expecting positive assertion. */
	public function assertIfCanRemoveHeader ()
	{
		$headers = new HeaderBag();
		$headers
			->add('Accept', 'text/html, application/xhtml+xml')
			->remove('Accept');

		$this->assertFalse($headers->has('Accept'));
	}

	/** @test Expecting positive assertion. */
	public function assertIfCanCheckIfHeaderHasContent ()
	{
		$headers = new HeaderBag();
		$headers->add('Accept', 'text/html, application/xhtml+xml');

		$this->assertTrue($headers->is('Accept','application/xhtml+xml'));
	}

	/** @test Expecting negative assertion. */
	public function assertIfCannotCheckIfHeaderHasContent ()
	{
		$headers = new HeaderBag();
		$headers->add('Accept', 'text/html, application/xhtml+xml');

		$this->assertFalse($headers->is('Accept','application/xml'));
	}

	/** @test Expecting negative assertion. */
	public function assertIfCanExportToCurl ()
	{
		$headers = new HeaderBag();
		$headers->add('Accept', 'text/html, application/xhtml+xml');

		$this->assertSame(
			['accept: text/html, application/xhtml+xml'],
			$headers->cURL()
		);
	}

	/** @test Expecting negative assertion. */
	public function assertIfCanImportHeadersFromString ()
	{
		$raw = 'HTTP/1.x 200 OK\n
		Transfer-Encoding: chunked\n
		Date: Sat, 28 Nov 2009 04:36:25 GMT\n
		Server: LiteSpeed\n
		Connection: close\n
		X-Powered-By: W3 Total Cache/0.8\n
		Pragma: public\n
		Expires: Sat, 28 Nov 2009 05:36:25 GMT\n
		Etag: "pub1259380237;gz"\n
		Cache-Control: max-age=3600, public\n
		Content-Type: text/html; charset=UTF-8\n
		Last-Modified: Sat, 28 Nov 2009 03:50:37 GMT\n
		X-Pingback: https://net.tutsplus.com/xmlrpc.php\n
		Content-Encoding: gzip\n
		Vary: Accept-Encoding, Cookie, User-Agent';

		$headers = HeaderBag::prepare($raw);

		$this->assertSame(
			[
				'transfer-encoding: chunked',
				'date: Sat, 28 Nov 2009 04:36:25 GMT',
				'server: LiteSpeed',
				'connection: close',
				'x-powered-by: W3 Total Cache/0.8',
				'pragma: public',
				'expires: Sat, 28 Nov 2009 05:36:25 GMT',
				'etag: "pub1259380237;gz"',
				'cache-control: max-age=3600, public',
				'content-type: text/html; charset=UTF-8',
				'last-modified: Sat, 28 Nov 2009 03:50:37 GMT',
				'x-pingback: https://net.tutsplus.com/xmlrpc.php',
				'content-encoding: gzip',
				'vary: Accept-Encoding, Cookie, User-Agent'
			],
			$headers->cURL()
		);
	}
}