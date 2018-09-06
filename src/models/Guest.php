<?php

namespace everyday\waitwhile\models;

use craft\base\Model;

class Guest extends Model
{
    public $name, $email, $phone, $notes, $redirect;
    public $state = 'waiting';

    public function init()
    {
        parent::init();
    }

    public function rules()
    {
        return [
            [['name', 'state', 'redirect'], 'required'],
        ];
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
    public function setState($value)
    {
        $this->state = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setRedirect($value)
    {
        $this->redirect = $value;

        return $this;
    }
}
