# Api Client Starter-Kit

This open-source library is provided for cases where you need to do APIs calls with large flexibility that officials SDK may not provide out-of-the-box. When using this Starter-Kit, you will be able to make better cURL requests with a smart managing of requests and responses.

## Installation

### Composer

1. At you console, in your project folder, type `composer require piggly/php-api-client`;
2. Don't forget to add Composer's autoload file at your code base `require_once('vendor/autoload.php);`.

### Manual install

1. Download or clone with repository with `git clone https://github.com/piggly-dev/php-api-client.git`;
2. After, goes to `cd /path/to/piggly/php-api-client`;
3. Install all Composer's dependencies with `composer install`;
4. Add project's autoload file at your code base `require_once('/path/to/piggly/php-api-client/vendor/autoload.php);`.

## Dependencies 

The library has the following external dependencies:

* [PHP cURL extension](https://www.php.net/manual/en/intro.curl.php);
* [PHP JSON extension](https://php.net/manual/en/book.json.php);
* PHP 7.2+.

## How to?

### Configuration

This library provides, first, a `Configuration` object. You must create it before send any request. You may create many `Configuration` objects to provide different environments or you could use `.env` files to manages it.

See below all configurations:

Configuration | Method | Description
--- | --- | ---
Api Keys | `apiKey()` and `getApiKey()` | Add a new api key. You may have many.
Access Token | `accessToken()` and `getAccessToken()` | Add an access token.
Client Username | `username()` and `getUsername()` | HTTP Client Username to Basic Authentication.
Client Password | `password()` and `getPassword()` | HTTP Client Secret to Basic Authentication.
HTTP Headers | `headers()` and `cloneHeaders()` | Manage the defaults HTTP Headers.
Host | `host()` and `getHost()` | Host URL to send Requests, without paths.
UserAgent | `userAgent()` and `getUserAgent()` | UserAgent which is making Requestes.
Timeout | `timeout()` and `getTimeout()` | The maximum number of seconds to allow cURL functions to execute.
Connection Timeout | `connectionTimeout()` and `getConnectionTimeout()` | The maximum amount of time in seconds that is allowed to make the connection to the server
Proxy Host | `proxyHost()` and `getProxyHost()` | Host for proxying.
Proxy Port | `proxyPort()` and `getProxyPort()` | Port for proxying.
Proxy Type | `proxyType()` and `getProxyType()` | cURL proxy type, e.g. CURLPROXY_HTTP or CURLPROXY_SOCKS5.
Proxy User | `proxyUser()` and `getProxyUser()` | User for proxying.
Proxy Password | `proxyPassword()` and `getProxyPassword()` | Password for proxying.
Custom Configuration | `custom()` and `getCustom()` | Add any custom configuration.
SSL Validation | `verifySSL()` and `shouldVerifySSL()` | If should or not do a SSL verification.
Default Configuration | `default` and `getDefault()` | A static default configuration objects. It will be available to all Configuration instances.

Development configurations:

With `debug()` method you will be able to enable requests debugging. You also may set a `Logger` to log messages across code lifetime. The `logger()` method is expecting a `Logger` from `monolog/monolog` package. Only when a `Logger` is set the `log()` method will output messages into `Logger`.

> **NOTE**: Debug messages will only be output to `Logger` when `debug()` mode is active.

You can also control configurations by usign `.env` files with `env()` method. You must set then the `path` where `.env` file is located and the current `env`. When `env` is `dev`, `Configuration` object will try to get `.env.dev` file at your `path`. The `.env` variables are:

```
CURL_CLIENT_USERNAME=username
CURL_CLIENT_PASSWORD=password
CURL_DEBUG=true
CURL_HTTP_HOST=http://localhost
CURL_HTTP_USER_AGENT=MyServer/1.0
CURL_HTTP_TIMEOUT=60
CURL_HTTP_CONN_TIMEOUT=60
CURL_PROXY_HOST=proxy://localhost
CURL_PROXY_PORT=5050
CURL_PROXY_USER=proxyusername
CURL_PROXY_PASSWORD=proxypassword
```

> See at [.env.example](.env.example) to see an example.

### Requests

After configuration is done, you are ready to use `Request` object. You may set a `Configuration` object to `Request` constructor or it will get the default configuration.

Then, you must start with request's methods: `delete()`, `head()`, `get()`, `options()`, `patch()`,`post()` and `put()`. After you will be able to manipulate request with methods below:

Method | Description
--- | ---
`headers()` | Manages all request headers.
`applyHeaders()` | Applies headers to request headers.
`basicAuth()` | Apply basic authorization header.
`authorization()` | Apply authorization header with an api key.
`data()` | Set the post data.
`params()` | Set parameters replaces to URI. At URI, `/posts/{id}` you may set `params(['id', 1])`, then it will be replaced `/posts/1`.
`query()` | Set query string parameters.
`responseType()` | Set the response type as `\SplFileObject`, `string` or `array`.

When your request is done, you need to use `call()` method. The `call()` method will:

* Throw an `ApiResponseException`, if response fails;
* Throw an `ApiResponseException`, if response has a non 2xx response;
* Return an `array` in format `[$body, $httpCode, $headers]` which you can handle with `list($body, $httpCode, $headers) = $request->call();`.

#### Headers

To manage headers, there is a `HeaderBag` object that will have all needed methods to better handle with headers.

## Samples

You can see very lightweight samples at **/samples** folder.

## Changelog

See the [CHANGELOG](CHANGELOG.md) file for information about all code changes.

## Testing the code

This library uses the PHPUnit. We carry out tests of all the main classes of this application.

```bash
vendor/bin/phpunit
```

> You must always run tests with PHP 7.2 and greater.

## Contributions

See the [CONTRIBUTING](CONTRIBUTING.md) file for information before submitting your contribution.

## Credits

- [Caique Araujo](https://github.com/caiquearaujo)
- [All contributors](../../contributors)

## Support the project

Piggly Studio is an agency located in Rio de Janeiro, Brazil. If you like this library and want to support this job, be free to donate any value to BTC wallet `3DNssbspq7dURaVQH6yBoYwW3PhsNs8dnK` ❤.

## License

MIT License (MIT). See [LICENSE](LICENSE).