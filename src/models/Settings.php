<?php

namespace everyday\waitwhile\models;

use craft\base\Model;

class Settings extends Model
{
    public $api_key;
    public $waitlist_id;

    public function init()
    {
        parent::init();
    }

    public function rules()
    {
        return [
            [['api_key', 'waitlist_id'], 'required'],
        ];
    }
}
