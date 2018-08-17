<?php

namespace everyday\waitwhile\models;

use craft\base\Model;

class Settings extends Model
{
    protected $apiKey;

    public function init()
    {
        parent::init();
    }

    public function rules()
    {
        return [
            [['api_key'], 'required'],
        ];
    }
}
