<?php

namespace brianjhanson\Storybook;

use brianjhanson\storybook\models\Settings;
use brianjhanson\storybook\services\Story;
use brianjhanson\storybook\twigextensions\StorybookTwigExtension;
use Craft;
use craft\base\Plugin;

/**
 * @property Story $story
 */
class Storybook extends Plugin
{
    /**
     * @inerhitdoc
     */
    public $hasCpSettings = true;

    public function init()
    {
        parent::init();

        if (Craft::$app->request->getIsSiteRequest()) {
            // Add in our Twig extension
            $extension = new StorybookTwigExtension();
            Craft::$app->view->registerTwigExtension($extension);
        }

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
