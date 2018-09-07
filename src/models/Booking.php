<?php

namespace everyday\waitwhile\models;

use craft\base\Model;

class Booking extends Model
{
    public $name, $email, $phone, $notes, $duration, $time;
    public $state = 'waiting';

    public function init()
    {
        parent::init();
    }

    public function rules()
    {
        return [
            [
                ['name', 'state', 'duration', 'time'],
                'required'
            ],
            [
                'phone', 'phoneCountryCodeRequired'
            ],
            [
                'email', 'emailValid'
            ]
        ];
    }

    /**
     * @param $attribute
     */
    public function phoneCountryCodeRequired($attribute)
    {
        if(substr($this->$attribute, 0, strlen('+')) !== '+'){
            $this->addError($attribute, \Craft::t('everyday-waitwhile', 'phone_landcode_required'));
        }
    }

    /**
     * @param $attribute
     */
    public function emailValid($attribute)
    {
        if (!filter_var($this->$attribute, FILTER_VALIDATE_EMAIL)) {
            $this->addError($attribute, \Craft::t('everyday-waitwhile', 'email_invalid'));
        }
    }

    /**
     * @param $value
     * @return $this
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setEmail($value)
    {
        $this->email = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPhone($value)
    {
        $this->phone = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setNotes($value)
    {
        $this->notes = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setDuration($value)
    {
        $this->duration = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setTime($value)
    {
        $this->time = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setState($value)
    {
        $this->state = $value;

        return $this;
    }
}
