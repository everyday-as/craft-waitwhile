<?php

namespace everyday\waitwhile\models;

use craft\base\Model;
use everyday\waitwhile\Plugin;

class Booking extends Model
{
    public $name, $email, $phone, $notes, $duration, $time, $resourceIds, $birthdate;
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
            ],
            [
                'birthdate', 'birthdateValid'
            ]
        ];
    }

    /**
     * @param $attribute
     */
    public function phoneCountryCodeRequired($attribute)
    {
        if (substr($this->$attribute, 0, strlen('+')) !== '+') {
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
     * @param $attribute
     */
    public function birthdateValid($attribute)
    {
        if (strlen($this->$attribute) !== 6 || !is_numeric($this->$attribute)) {
            $this->addError($attribute, \Craft::t('everyday-waitwhile', 'birthdate_invalid'));
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
     * @return Guest
     */
    public function setBirthdate($value): self
    {
        $this->notes = '(' . \Craft::t('everyday-waitwhile', 'birthdate') . ' ' . $value . '): ' . $this->notes;

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

    /**
     * @param array $values
     * @return Booking
     */
    public function setResourceIds(array $values): self
    {
        $this->resourceIds = $values;

        return $this;
    }

    /**
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $array = parent::toArray($fields, $expand, $recursive);

        foreach ($array as $key => $value) {
            if (is_null($value) || empty($value)) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}
