<?php

namespace everyday\waitwhile\twig;

use everyday\waitwhile\models\Waitwhile;

class WaitwhileTwigExtension extends \Twig_Extension
{
    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return array(
            new \Twig_Filter('waitwhile_unix_ms_to_human', array($this, 'waitwhile_unix_ms_to_human')),
            new \Twig_Filter('waitwhile_unix_ms_to_minutes', array($this, 'waitwhile_unix_ms_to_minutes')),
        );
    }

    /**
     * @param $value
     * @return false|string
     */
    function waitwhile_unix_ms_to_human($value)
    {
        return Waitwhile::unix_ms_to_human($value);
    }

    /**
     * @param $value
     * @return false|string
     */
    function waitwhile_unix_ms_to_minutes($value)
    {
        return Waitwhile::unix_ms_to_minutes($value);
    }
}