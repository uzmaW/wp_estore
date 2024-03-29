# Changelog

All notable changes to `Firestore PHP` will be documented in this file.

## 3.1.0 - 2023-05-22
 - Feature: Add ability to use special characters in filenames. Thanks to PR #2 by @backendrulz 

## 3.0.0 - 2023-03-23
 - Added support for Guzzle 7
 - Added support for PHP 8
 - Minimum requirement PHP 7.3
 - Corrected several linting errors

## 2.0.1 - 2019-02-25
 - Documentation error and typos fixed.
 - Saving last response when Guzzle's `BadResponseException` exception throws.
 - Had to use `git mv` to rename files changed in `2.0.0`
 - Added `has` method to validate key existence.

## 2.0.0 - 2019-02-23
 - Added Firebase Authentication
 - All files prefixed changed from `FireStore` to `Firestore` (notice the `s` in *store*)
 - Added `Bytes` support.
 - Exception handling support added.
 - Support added to list all documents, batch listing with query parameter.
 - Pagination support for bulk and document listing.
 - Improved naming convention throughout the package.
 - `FireStoreApiClient` changed to `FirestoreClient`
 - Documentation updated

## 1.0.1 - 2019-01-16
 - Add method for casting floating point values
 - Document ID flipped on `getDocument` method

## 1.0.0 - 2018-04-20
 - Initial release
