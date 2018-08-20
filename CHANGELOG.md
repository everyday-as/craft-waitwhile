# Release Notes for Everyday Waitwhile

## 1.0.4 - 2018-08-20

### Added
- Added localization
- `Waitwhile@createWaitingGuest` now adds a waiting guest to Waitwhile
- Added a `everyday-waitwhile/frontend/queue-form` template for submitting a `Waitwhile@createWaitingGuest` action
- Added a `BookingController`, a `Booking` model and `createBooking` method 

## 1.0.3 - 2018-08-20

### Fixed
- Swapped out cURL in favour of GuzzleHttp
- This fixes `curl_setopt(): You must pass either an object or an array with the CURLOPT_HTTPHEADER argument`

### Added
- Added a changelog (thanks captain obvious!)
- Added a `Guest` model that can be served to any create guest method
- Added a `createWaitingGuest` method in Waitwhile, requires an instance of `Guest`

{note} Testing some controllers, ignore this for now