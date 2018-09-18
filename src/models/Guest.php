<?php

namespace everyday\waitwhile\models;

use craft\base\Model;

class Guest extends Model
{
    public $name, $email, $phone, $notes, $birthdate;
    public $state = 'waiting';

    public function init()
    {
        parent::init();
    }

    public function rules()
    {
        return [
            [
                ['name', 'state'], 'required'
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
     * @return Guest
     */
    public function setName($value): self
    {
        $this->name = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Guest
     */
    public function setEmail($value): self
    {
        $this->email = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Guest
     */
    public function setPhone($value): self
    {
        $this->phone = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Guest
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
        $this->birthdate = $value;

        return $this;
    }

    /**
     * @param $value
     * @return Guest
     */
    public function setState($value): self
    {
        $this->state = $value;

        return $this;
    }
}
