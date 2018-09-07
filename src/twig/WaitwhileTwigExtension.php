<?php

namespace everyday\waitwhile\twig;

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
        return gmdate("H:i", $value / 1000);
    }

    /**
     * @param $value
     * @return false|string
     */
    function waitwhile_unix_ms_to_minutes($value)
    {
        return round($value / 1000 / 60);
    }
}