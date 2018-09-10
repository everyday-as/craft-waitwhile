<?php

namespace everyday\waitwhile\controllers;

use craft\web\Controller;
use everyday\waitwhile\models\Booking;
use everyday\waitwhile\models\Guest;
use everyday\waitwhile\models\Waitwhile;

class BookingController extends Controller
{
    protected $allowAnonymous = true;

    public function actionIndex()
    {
        $params = \Craft::$app->request->bodyParams;

        $phone = $params['phone'] ?? null;

        // if landcode hidden input field is set, append this to phone number unless phone number starts with +
        if($phone !== null && isset($params['country_code']) && substr($phone, 0, strlen('+')) !== '+'){
            $phone = '+' . $params['country_code'] . $phone;
        }

        $booking = (new Booking())
            ->setEmail($params['email'] ?? null)
            ->setPhone($phone)
            ->setName($params['name'])
            ->setNotes($params['notes'] ?? null)
            ->setDuration($params['duration'])
            ->setTime($params['time']);

        if($booking->validate()){
            $waitwhile = new Waitwhile();
            $waitwhile->createBooking($booking);

            $this->redirectToPostedUrl();
        }

        \Craft::$app->urlManager->setRouteParams(array(
            'errors' => $booking->errors
        ));
    }

    /**
     * @param string $date
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionTimes(string $date): string
    {
        $waitwhile = new Waitwhile();

        return json_encode($waitwhile->getBookingTimesForDay($date));
    }
}