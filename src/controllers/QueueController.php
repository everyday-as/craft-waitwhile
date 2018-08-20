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

        $guest = (new Guest())->setEmail($params['email'])->setPhone($params['phone'])->setName($params['name']);

        if($guest->validate()){
            $waitwhile = new Waitwhile();
            $waitwhile->createWaitingGuest($guest);

            $this->redirectToPostedUrl();
        }

        \Craft::$app->urlManager->setRouteParams(array(
            'errors' => $guest->errors
        ));
    }
}