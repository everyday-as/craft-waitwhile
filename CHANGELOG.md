# Changelog

## 1.0.3

- Added a changelog (thanks captain obvious!)
- Swapped out cURL in favour of GuzzleHttp
- This also fixes `curl_setopt(): You must pass either an object or an array with the CURLOPT_HTTPHEADER argument`
- Added a `Guest` model that can be served to any create guest method
- Added a `createWaitingGuest` method in Waitwhile, requires an instance of `Guest`
- Testing some controllers, ignore this for now