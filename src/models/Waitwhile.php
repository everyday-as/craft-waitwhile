<?php

namespace everyday\waitwhile\models;

use craft\base\Model;
use everyday\waitwhile\Plugin;
use yii\caching\Cache;
use yii\caching\FileCache;

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

        // set headers
        $this->headers = [
            'apiKey: ' . $this->settings->api_key
        ];
    }

    /**
     * @param $endpoint
     * @param string $method
     * @param array $data
     * @return array
     */
    public function curlRequest($endpoint, $method = 'GET', $data = []): array
    {
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, 'https://api.waitwhile.com/v1/' . $endpoint);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch,CURLOPT_USERAGENT, "Everyday Waitwhile Integration For Craft CMS");

        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result;
    }

    /**
     * @return array
     */
    public function getWaitlist(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileWaitlist", function ($cache) use($settings) {
            return $this->waitlist === null ? $this->curlRequest('waitlists/' . $this->settings->waitlist_id) : $this->waitlist;
        }, 300);
    }

    /**
     * @return array
     */
    public function getAllWaitlists(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileAllWaitlists", function ($cache) use($settings) {
            return $this->waitlists === null ? $this->curlRequest('waitlists') : $this->waitlists;
        }, 300);
    }

    /**
     * @return array
     */
    public function getWaitlistStatus(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileWaitlistStatus", function ($cache) use($settings) {
            return $this->waitlistStatus === null ? $this->curlRequest('waitlists/' . $this->settings->waitlist_id . '/status') : $this->waitlistStatus;
        }, 300);
    }

    /**
     * @return array
     */
    public function getWaitingGuests(): array
    {
        $settings = $this->settings;

        return \Craft::$app->cache->getOrSet("waitwhileWaitingGuests", function ($cache) use($settings) {
            return $this->guestsWaiting === null ? $this->curlRequest('waitlists/' . $this->settings->waitlist_id . '/waiting') : $this->guestsWaiting;
        }, 300);
    }

    public function createWaitingGuest(): array
    {

    }

    public function createBooking(): array
    {

    }
}
