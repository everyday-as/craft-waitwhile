<?php

namespace everyday\waitwhile\controllers;

use craft\web\Controller;
use everyday\waitwhile\models\Booking;
use everyday\waitwhile\models\Guest;
use everyday\waitwhile\models\Waitwhile;

class BookingController extends Controller
{
    protected $allowAnonymous = true;

    /**
     * @return false|string|\yii\web\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $isJavascript = (bool)\Craft::$app->request->getHeaders()['javascript-request']
            || \Craft::$app->request->getIsAjax();

        $params = \Craft::$app->request->getBodyParams();

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

            if(!$waitwhile->error) {
                if (!$isJavascript) {
                    return $this->redirect(isset($params['redirect']) ? $params['redirect'] : '/');
                }

                return json_encode(['success' => true]);
            }

            // error:
            if(!$isJavascript) {
                return \Craft::$app->urlManager->setRouteParams(array(
                    'errors' => $waitwhile->errors
                ));
            }

            return json_encode(['success' => false, 'errors' => array_values(call_user_func_array('array_merge', $waitwhile->errors))]);
        }

        if(!$isJavascript) {
            \Craft::$app->urlManager->setRouteParams(array(
                'errors' => $booking->errors
            ));
        }

        return json_encode(['success' => false, 'errors' => array_values(call_user_func_array('array_merge', $booking->errors))]);
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionTimes(): string
    {
        $params = \Craft::$app->request->getBodyParams();

        $waitwhile = new Waitwhile();

        return json_encode($waitwhile->getBookingTimesForDay($params['date']));
    }
}