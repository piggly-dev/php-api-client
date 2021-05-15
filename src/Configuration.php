<?php
namespace Piggy\ApiClient\Client;

class Configuration
{
	public function apiKey ( string $identifier, string $key )
	{ $this->_keys['key'][$identifier] = $key; return $this; }

	public function getApiKey ( string $identifier ) : ?string
	{ return $this->_keys['key'][$identifier] ?? null; }

	public function apiKeyPrefix ( string $identifier, string $key )
	{ $this->_keys['prefix'][$identifier] = $key; return $this; }

	public function getApiKeyPrefix ( string $identifier ) : ?string
	{ return $this->_keys['prefix'][$identifier] ?? null; }

	public function accessToken ( string $accessToken )
	{ $this->_accessToken = $accessToken; }

	public function getAccessToken () : ?string
	{ return $this->_accessToken ?? null; }

	public function header ( string $key, string $content )
	{ $this->_headers[$key] = $content; return $this; }

	public function unsetHeader ( string $key )
	{ unset($this->_headers[$key]); return $this; }

	public function getHeaders () : array
	{ return $this->_headers ?? []; }

	public function host ( string $host )
	{ $this->_host = $host; return $this; }

	public function getHost () : ?string
	{ return $this->_host ?? null; }

	public function userAgent ( string $userAgent )
	{ $this->_userAgent = $userAgent; return $this; }

	public function getUserAgent () : ?string
	{ return $this->_userAgent ?? null; }
}