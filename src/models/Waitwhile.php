<?php

namespace everyday\waitwhile\models;

use craft\base\Model;
use everyday\waitwhile\Plugin;

class Waitwhile extends Model
{
    public $waitlist = null;
    public $waitlists = null;
    public $waitlistStatus = null;
    public $guests = null;
    public $guestsWaiting = null;
    public $bookings = null;
    public $bookingsFrom = null;
    public $resources = null;
    public $error = false;
    public $errors = [];

    protected $headers;
    protected $settings;

    protected $cache;

    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        // fetch settings
        $this->settings = Plugin::getInstance()->settings;

        // do not continue if api key is not set
        if($this->settings->api_key === null){
            return;
        }
    }

    /**
     * Fetch the waitwhile session
     *
     * @return mixed
     * @throws \craft\errors\MissingComponentException
     */
    public function getSession()
    {
        return \Craft::$app->getSession()->get('waitwhile');
    }

    /**
     * @param $endpoint
     * @param string $method
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeRequest($endpoint, $method = 'GET', $data = []): array
    {
        try {
            $client = \Craft::createGuzzleClient([
                'headers' => [
                    'User-Agent' => 'Craft/' . \Craft::$app->getVersion() . ' ' . \GuzzleHttp\default_user_agent(),
                    'apiKey' => $this->settings->api_key
                ],
                'base_uri' => 'https://api.waitwhile.com/v1/',
                'form_params' => $data,
            ]);

            $res = $client->request($method, $endpoint)->getBody()->getContents();

            return json_decode($res, true);
        } catch (\Exception $e) {
            $this->error = true;
            $this->errors[] = ['api' => 'Received an invalid response from the Waitwhile API'];
        }

        return [];
    }

    /**
     * @return array
     */
    public function getWaitlist(): array
    {
        return \Craft::$app->cache->getOrSet("waitwhileWaitlist", function ($cache) {
            return $this->waitlist === null ? $this->makeRequest('waitlists/' . $this->settings->waitlist_id) : $this->waitlist;
        }, 300);
    }

    /**
     * @return array
     */
    public function getAllWaitlists(): array
    {
        return \Craft::$app->cache->getOrSet("waitwhileAllWaitlists", function ($cache) {
            return $this->waitlists === null ? $this->makeRequest('waitlists') : $this->waitlists;
        }, 300);
    }

    /**
     * @return array
     */
    public function getWaitlistStatus(): array
    {
        return \Craft::$app->cache->getOrSet("waitwhileWaitlistStatus", function ($cache) {
            return $this->waitlistStatus === null ? $this->makeRequest('waitlists/' . $this->settings->waitlist_id . '/status') : $this->waitlistStatus;
        }, 300);
    }

    /**
     * @return array
     */
    public function getWaitingGuests(): array
    {
        return \Craft::$app->cache->getOrSet("waitwhileWaitingGuests", function ($cache) {
            return $this->guestsWaiting === null ? $this->makeRequest('waitlists/' . $this->settings->waitlist_id . '/waiting') : $this->guestsWaiting;
        }, 300);
    }

    /**
     * @return array
     */
    public function getBookings(): array
    {
        return \Craft::$app->cache->getOrSet("waitwhileBookings", function ($cache) {
            return $this->bookings === null ? $this->makeRequest('waitlists/' . $this->settings->waitlist_id . '/bookings') : $this->bookings;
        }, 300);
    }

    /**
     * @param int $fromTime
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBookingsFrom(int $fromTime = 0): array
    {
        return $this->bookingsFrom === null ? $this->makeRequest('waitlists/' . $this->settings->waitlist_id . '/bookings?fromTime=' . $fromTime) : $this->bookings;
    }

    /**
     * @param Guest $guest
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createWaitingGuest(Guest $guest): array
    {
        return $this->makeRequest('waitlists/' . $this->settings->waitlist_id . '/guests', 'POST', $guest->toArray());
    }

    /**
     * @param Booking $booking
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createBooking(Booking $booking): array
    {
        return $this->makeRequest('waitlists/' . $this->settings->waitlist_id . '/bookings', 'POST', $booking->toArray());
    }

    /**
     * @return array
     */
    public function getResources(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileResources", function ($cache) use($settings) {
            return $this->resources === null ? $this->makeRequest('resources') : $this->resources;
        }, 300);
    }

    /**
     * @param string $date
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBookingTimesForDay(string $date): array
    {
        // start of $date
        $startOfDate = (new \DateTime($date))->setTime(0,0);
        $startOfDateUnixMs = $startOfDate->getTimestamp() * 1000;
        $currentTimeUnixMs = (new \DateTime())->getTimestamp() * 1000;

        // bookings from start of today
        $bookings = $this->getBookingsFrom($startOfDateUnixMs);

        $waitlistHours = $this->getWaitlist()['waitlistHours'];
        $currentDayAbbreviation = strtolower($startOfDate->format('D'));
        $waitlistHoursToday = $waitlistHours[$currentDayAbbreviation]['periods'];
        $isOpen = $waitlistHours[$currentDayAbbreviation]['isOpen'];

        $bookingLength = $this->settings['booking_length'];

        $times = [];

        $bookingLengthUnixMs = $bookingLength * 60 * 1000;
        $currentTimeUnixMsOnlyToday = $currentTimeUnixMs - $startOfDateUnixMs;

        foreach($waitlistHoursToday as $period){
            // we need to foreach each period to create available times within them based on $bookingLengthUnixMs

            $start = $period['from'];
            $end = $period['to'];

            while($start < $end){
                $realTimeUnixMs = $startOfDateUnixMs + $start;
                $nextStart = $start + $bookingLengthUnixMs;
                $nextStartRealTime = $startOfDateUnixMs + $nextStart;

                // $currentTimeUnixMsOnlyToday must be less than $start
                if($currentTimeUnixMsOnlyToday < $start) {
                    // we need to check if available based on $bookings array
                    $available = true;

                    foreach($bookings as $booking) {
                        // check if $booking['time'] is not the same as or within $realTimeUnixMs and $nextStart
                        if($booking['time'] >= $realTimeUnixMs && $booking['time'] < $nextStartRealTime) $available = false;
                    }

                    // logic to add to $times array
                    $times[] = [
                        'start' => self::unix_ms_to_human($start),
                        'start_unix_ms' => $start,
                        'duration' => $bookingLengthUnixMs,
                        'available' => $available
                    ];
                }

                // increment $start with the length of $bookingLengthUnixMs
                $start = $nextStart;
            }
        }

        return [
            'isOpen' => $isOpen,
            'times' => $times,
        ];
    }

    /**
     * @param $value
     * @return false|string
     */
    public static function unix_ms_to_human($value)
    {
        return gmdate("H:i", $value / 1000);
    }

    /**
     * @param $value
     * @return false|string
     */
    public static function unix_ms_to_minutes($value)
    {
        return round($value / 1000 / 60);
    }
}
