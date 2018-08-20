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
     * @param $endpoint
     * @param string $method
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function makeRequest($endpoint, $method = 'GET', $data = []): array
    {
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
    }

    /**
     * @return array
     */
    public function getWaitlist(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileWaitlist", function ($cache) use($settings) {
            return $this->waitlist === null ? $this->makeRequest('waitlists/' . $this->settings->waitlist_id) : $this->waitlist;
        }, 300);
    }

    /**
     * @return array
     */
    public function getAllWaitlists(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileAllWaitlists", function ($cache) use($settings) {
            return $this->waitlists === null ? $this->makeRequest('waitlists') : $this->waitlists;
        }, 300);
    }

    /**
     * @return array
     */
    public function getWaitlistStatus(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileWaitlistStatus", function ($cache) use($settings) {
            return $this->waitlistStatus === null ? $this->makeRequest('waitlists/' . $this->settings->waitlist_id . '/status') : $this->waitlistStatus;
        }, 300);
    }

    /**
     * @return array
     */
    public function getWaitingGuests(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileWaitingGuests", function ($cache) use($settings) {
            return $this->guestsWaiting === null ? $this->makeRequest('waitlists/' . $this->settings->waitlist_id . '/waiting') : $this->guestsWaiting;
        }, 300);
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

    public function createBooking(): array
    {

    }
}
