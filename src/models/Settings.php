<?php

namespace everyday\waitwhile\models;

use craft\base\Model;

class Settings extends Model
{
    public function init()
    {
        parent::init();
    }

    public function rules()
    {
        return [
            [['view'], 'required'],
        ];
    }
}
