<?php

namespace everyday\waitwhile;

use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use craft\web\View;
use everyday\waitwhile\models\Settings;
use craft\web\twig\variables\CraftVariable;
use everyday\waitwhile\models\Waitwhile;
use everyday\waitwhile\twig\WaitwhileTwigExtension;
use yii\base\Event;
use craft\i18n\PhpMessageSource;

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

        // template root
        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $event) {
                $event->roots['_everyday-waitwhile'] = __DIR__ . '/templates/frontend';
            }
        );

        // translation source
        \Craft::$app->i18n->translations['everyday-waitwhile'] = [
            'class' => PhpMessageSource::class,
            'sourceLanguage' => 'en',
            'basePath' => __DIR__ . '/translations',
            'allowOverrides' => true,
        ];
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