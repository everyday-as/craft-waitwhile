# Release Notes for Everyday Waitwhile

## 1.1.4 - 2018-09-19
- Added a birthdate field to Booking and Guest. This is a useful field a lot of people have a need for. Validation is if it's numeric and 6 in length (ddmmyy).
- The above is sent as an addition to a note as I can not seem to make custom fields work at all
- Made error messages more obvious 

## 1.1.3 - 2018-09-13
- Added `craft.waitwhile.getBusinessHours` and `craft.waitwhile.getWaitlistHours` in a better format than the default returned by Waitwhile

## 1.1.2 - 2018-09-11
- Reverts 1.1.1 use `Controller@asJson` instead of `json_decode`

## 1.1.1 - 2018-09-11
- Refactored `BookingController` and `QueueController`. They now support JavaScript requests - if it's an ajax request you have to do nothing, 
but if it's an axios or other request you need to set the `Javascript-Request` header to 1 or true. 
When sending JavaScript requests you need to deal with the error and redirect logic yourself.
- Set response in waitwhile session for `BookingController` just like `QueueController` does
- Added `start_real_unix_ms` to times array for `Waitwhile@getBookingTimesForDay`
- Use `Controller@asJson` instead of `json_decode`

## 1.1.0 - 2018-09-10
- Added a `Waitwhile@getBookingTimesForDay('YYYY-MM-DD')` method that returns an array with the available times on the given day
- Added a `Booking::formatBookins` method, only used internally
- Added a booking length setting (in minutes)
- Added `everyday-waitwhile/booking/times` action

## 1.0.6 - 2018-09-07
- Added support for a hidden input field called `country_code` in submission for action `everyday-waitwhile/queue`
(input is without +, only the actual country code) 
- Added `notes` input to Queue and Booking
- Added same validation Guest received in 1.0.5 to Booking
- Make sure some fields truly are optional
- Require PHP 7.0 or above

## 1.0.5 - 2018-09-06
- Added a `Waitwhile@getBookings` method
- Added a `Waitwhile@getResources` method
- Create a `Waitwhile@getSession` method for the `Waitwhile` class to expose the API response on the following page
- The above means that `craft.waitwhile.getSession` grabs the previous API response
- Added validation rules for email and phone fields for the `Guest` model

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