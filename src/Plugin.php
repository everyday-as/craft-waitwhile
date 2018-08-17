<?php

namespace everyday\waitwhile;

use everyday\waitwhile\models\Settings;

class Plugin extends \craft\base\Plugin
{
    public $hasCpSettings = true;

    /**
     * Init plugin
     */
    public function init()
    {
        parent::init();

        // Custom initialization code goes here...
    }

    /**
     * @return \craft\base\Model|Settings|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \yii\base\Exception
     */
    protected function settingsHtml()
    {
        $settings = $this->getSettings();
        $settings->validate();

        return \Craft::$app->getView()->renderTemplate('waitwhile/_settings', [
            'settings' => $settings
        ]);
    }
}