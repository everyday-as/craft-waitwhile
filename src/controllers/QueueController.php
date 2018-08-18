<?php

namespace everyday\waitwhile\controllers;

use craft\web\Controller;

class QueueController extends Controller
{
    protected $allowAnonymous = true;

    public function actionIndex()
    {
        return 'QueueController@actionIndex()';
    }
}