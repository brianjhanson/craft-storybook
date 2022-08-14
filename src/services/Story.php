<?php

namespace brianjhanson\storybook\services;

use brianjhanson\storybook\Storybook;
use craft\helpers\FileHelper;
use craft\helpers\Json;

class Story extends \craft\base\Component
{
    public static function writeStoryFile(array $config)
    {
        $savePath = Storybook::getInstance()->getSettings()->getStoriesDirectory();

        FileHelper::clearDirectory($savePath, [
            'except' => ['.*', '.*/'],
        ]);

        $filepath = $savePath . '/test.json';
        FileHelper::writeToFile($filepath, Json::encode($config));
        echo $filepath;
    }
}
