<?php

namespace brianjhanson\storybook;

use brianjhanson\storybook\models\Settings;
use brianjhanson\storybook\services\Story;
use brianjhanson\storybook\twigextensions\StorybookTwigExtension;
use Craft;
use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;

/**
 * @property Story $story
 */
class Storybook extends Plugin
{
    /**
     * @var Storybook|null
     */
    public static ?Storybook $plugin = null;

    /**
     * @inerhitdoc
     */
    public bool $hasCpSettings = true;

    /**
     * @inheritdoc
     * @return void
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        if (Craft::$app->request->getIsSiteRequest()) {
            // Add in our Twig extension
            $extension = new StorybookTwigExtension();
            Craft::$app->view->registerTwigExtension($extension);
        }

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['storybook/preview/<component:.+>'] = 'storybook/stories/preview';
            }
        );

        $this->setComponents([
            'story' => Story::class
        ]);
    }


    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'storybook/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
