<?php

namespace brianjhanson\storybook\services;

use brianjhanson\storybook\Storybook;
use craft\helpers\App;
use craft\helpers\FileHelper;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use PhpParser\Node\Stmt\Echo_;
use const PHP_EOL;

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

    public static function generate($componentName = '*')
    {
        $templateDir = App::parseEnv('@templates');
        $files = FileHelper::findFiles($templateDir, [
            'only' => [$componentName . '.twig']
        ]);


        foreach ($files as $file) {
            $componentId = StringHelper::replace($file, $templateDir, '');
            $componentId = StringHelper::replace($componentId, '.twig', '');
            $componentId = StringHelper::trimLeft($componentId, '/');

            $storyData = [
                'title' => self::getComponentTitle($componentId),
                'parameters' => [
                    'server' => [
                        'id' => StringHelper::trimLeft($componentId, '/')
                    ]
                ],
                'args' => [],
                'argTypes' => [],
                'stories' => [
                    [
                        'name' => 'Default',
                        'args' => []
                    ]
                ]
            ];

            $fileName = StringHelper::split($componentId, '/');
            self::writeStory($componentId, $storyData);
        }
    }

    private static function writeStory($filename, $storyData)
    {
        $savePath = Storybook::getInstance()->getSettings()->getStoriesDirectory();
        $filepath = $savePath . '/' . $filename . '.stories.json';

        FileHelper::writeToFile($filepath, Json::encode($storyData));
        echo 'Successfully wrote ' . $filepath . PHP_EOL;
    }

    private static function sanitizeComponentTitle($part)
    {
        $string = StringHelper::trim($part, '_');
        return StringHelper::toTitleCase($string);
    }

    private static function getComponentTitle(string $componentId)
    {
        $parts = explode('/', $componentId);
        $title = [];

        foreach ($parts as $part) {
            $title[] = self::sanitizeComponentTitle($part);
        }

        return implode('/', $title);
    }
}
