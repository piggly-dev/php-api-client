<?php
namespace Piggy\ApiClient\Client;

class ApiClient
{
	public function call (
		string $resourcePath,
		string $httpMethod,
		array $queryParams,
		array $postData,
		array $headerParams,
		string $responseType,
		string $endpointPath
	)
	{
		$headers = [];
	}

	/**
	 * Parse HTTP headers to an array.
	 * 
	 * @param string $raw Raw HTTP Headers
	 * @return array Including all headers
	 */
	protected function _parseHTTPHeaders ( string $raw ) : array
	{
		$raw = explode('\n', $raw);
		$headers = [];

		foreach ( $raw as $header )
		{
			if ( \stripos($header, ':') === false )
			{ continue; }

			$header  = explode(':', $header, 2);
			$key     = trim($header[0]);
			$content = trim($header[1]);

			if ( !isset($headers[$key]) )
			{ $headers[$key] = $content; continue; }
			
			if ( \is_array($headers[$key]) )
			{ $headers[$key] = \array_merge($headers[$key], [$content]); continue; }

			$headers[$key] = \array_merge([ $headers[$key] ], [ $content ]);
		}

		return $headers;
	}
}