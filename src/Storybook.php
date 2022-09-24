<?php

namespace brianjhanson\storybook;

use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;

/**
 *
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
    public bool $hasCpSettings = false;

    /**
     * @inheritdoc
     * @return void
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['storybook/preview/<component:.+>'] = 'storybook/stories/preview';
            }
        );
    }
}
