<?php

namespace everyday\waitwhile;

use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use everyday\waitwhile\controllers\WaitwhileController;
use everyday\waitwhile\models\Settings;
use craft\web\twig\variables\CraftVariable;
use everyday\waitwhile\models\Waitwhile;
use everyday\waitwhile\twig\WaitwhileTwigExtension;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    public $hasCpSettings = true;

    /**
     * Init plugin
     */
    public function init()
    {
        parent::init();

        // pipe waitwhile variable to twig via global craft var
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('waitwhile', Waitwhile::class);
        });

        // extend twig
        if (\Craft::$app->request->getIsSiteRequest()) {
            // Add in our Twig extension
            $extension = new WaitwhileTwigExtension();
            \Craft::$app->view->registerTwigExtension($extension);
        }

        // register routes
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules[$this->id . '/queue'] = $this->id . '/queue';
        });
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

        return \Craft::$app->getView()->renderTemplate('everyday-waitwhile/_settings', [
            'settings' => $settings
        ]);
    }
}