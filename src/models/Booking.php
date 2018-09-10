<?php

namespace everyday\waitwhile\models;

use craft\base\Model;
use everyday\waitwhile\Plugin;

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
     * @return Booking
     */
    public function setName($value): self
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Booking
     */
    public function setEmail($value): self
    {
        $this->email = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Booking
     */
    public function setPhone($value): self
    {
        $this->phone = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Booking
     */
    public function setNotes($value): self
    {
        $this->notes = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Booking
     */
    public function setDuration($value): self
    {
        $this->duration = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Booking
     */
    public function setTime($value): self
    {
        $this->time = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Booking
     */
    public function setState($value): self
    {
        $this->state = $value;

        return $this;
    }
}
