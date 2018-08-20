# Release Notes for Everyday Waitwhile

## 1.0.3 - 2018-08-20

### Fixed
- Swapped out cURL in favour of GuzzleHttp
- This fixes `curl_setopt(): You must pass either an object or an array with the CURLOPT_HTTPHEADER argument`

### Added
- Added a changelog (thanks captain obvious!)
- Added a `Guest` model that can be served to any create guest method
- Added a `createWaitingGuest` method in Waitwhile, requires an instance of `Guest`

{note} Testing some controllers, ignore this for now