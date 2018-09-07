<?php

namespace everyday\waitwhile\controllers;

use craft\web\Controller;
use everyday\waitwhile\models\Guest;
use everyday\waitwhile\models\Waitwhile;

class QueueController extends Controller
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

        $guest = (new Guest())
            ->setEmail($params['email'] ?? null)
            ->setPhone($phone)
            ->setNotes($params['notes'] ?? null)
            ->setName($params['name']);

        if($guest->validate()){
            $waitwhile = new Waitwhile();

            $response = $waitwhile->createWaitingGuest($guest);

            if(!$waitwhile->error){
                \Craft::$app->getSession()->set('waitwhile', $response);
                return $this->redirect(isset($params['redirect']) ? $params['redirect'] : '/');
            }

            // a non 2xx response:
            return \Craft::$app->urlManager->setRouteParams(array(
                'errors' => $waitwhile->errors
            ));
        }

        return \Craft::$app->urlManager->setRouteParams(array(
            'errors' => $guest->errors
        ));
    }
}