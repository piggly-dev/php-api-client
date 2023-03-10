# Changelog

## `1.0.0`

* First release.

## `1.0.1` at `2021-05-29`

* Fix `json_decode()` as associative array at `Request::call()` method.

## `1.0.2` at `2021-05-29`

* Fix response error at `Request::call()` method.

## `1.0.3` at `2021-07-19`

* Allow `null` headers at `ApiResponseException`.

## `1.0.4` at `2021-08-02`

* [FIX] Header bag is not JSON when $content is empty.

## `1.0.5` at `2021-08-04`

* [FIX] Allowing post data as string.

## `1.0.6` at `2021-08-26`

* [ADD] Custom cURL options with client configuration.

## `1.0.7` at `2021-08-26`

* [FIX] Convert body to string when throwing an `ApiResponseException`.
* [ADD] Method to throw an `ApiResponseException` with some default request data.

## `1.0.8` at `2022-03-27`

* [ADD] Methods to add queries and parameter.
* [FIX] Fix URI builder on Request class.

## `1.0.9` at `2022-09-28`

* [ADD] Abstract model and models.
* [ADD] Abstract classes for API wrapper and API endpoints;
* [ADD] Clone method to `Configuration`;
* [ADD] Enviroment interface to manipulate client configuration.

## `1.0.10` at `2022-09-28`

* [FIX] Invalid field name and timezone implementation at `CredentialModel`.

## `1.0.11` at `2022-09-30`

* [ADD] Implementation to an `AbstractEnvironment`.

## `1.0.12` at `2022-10-03`

* [ADD] Allow get query string and url params on `Request`;
* [CHANGE] Method `getUri` became public at `Request`;
* [CHANGE] Wrong method name at `ApplicationModel` fixed to `createEnvironment`;

## `1.1.0` at `2022-10-14`

* [ADD] Payload abstract class.


## `1.2.0` at `2022-10-14`

* [ADD] CS Fixer;
* [CHANGE] `addQuery()` will be `appendQuery()` method now;
* [FIX] Invalid return data to `delete()` method;
* [SUPPORT] PHP 8.0+.

## `2.0.0` at `2023-03-04`

* Many break changes, including a new Response object and changes on ApiResponseExpection properties.
* Some part of the code was optimized and some methods were deprecated.
* You must see code for details and changes.

## `2.1.0` at `2023-03-10`

* Replaced ApplicationModel with ApplicationInterface.