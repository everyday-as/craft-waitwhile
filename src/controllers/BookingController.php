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

        $booking = (new Booking())
            ->setEmail($params['email'])
            ->setPhone($params['phone'])
            ->setName($params['name'])
            ->setDuration($params['time'])
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
}