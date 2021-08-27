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
