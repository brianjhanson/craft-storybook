<?php

namespace brianjhanson\storybook\services;

use brianjhanson\storybook\Storybook;
use Craft;
use craft\helpers\App;
use craft\helpers\ArrayHelper;
use craft\helpers\FileHelper;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use craft\web\View;
use Exception;
use Twig\Environment;

class Story extends \craft\base\Component
{
    public static function story(&$context, $config, $self)
    {
        $args = $config['args'];

        foreach ($args as $key => $defaultValue) {
            $context[$key] = $context[$key] ?? $defaultValue ?? '';
        }

        if (Craft::$app->request->getIsConsoleRequest()) {
            self::generate($self, $config);
        }
    }

    public static function generate($componentName = '*', $config = [])
    {
        $templateDir = App::parseEnv('@templates');

        // Normalize the component name
        $componentName = StringHelper::replace($componentName, '.twig', '');
        $componentName = StringHelper::replace($componentName, $templateDir, '');

        $files = FileHelper::findFiles($templateDir, [
            'only' => [$componentName . '.twig']
        ]);

        foreach ($files as $file) {
            $componentId = StringHelper::replace($file, $templateDir, '');
            $componentId = StringHelper::trimLeft($componentId, '/');
            $componentId = StringHelper::replace($componentId, '.twig', '');

            $storyData = ArrayHelper::merge([
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
                    ],
                ]
            ], $config);

            self::writeStory($componentId, $storyData);
        }
    }

    private static function writeStory($filename, $storyData)
    {
        $savePath = Storybook::getInstance()->getSettings()->getStoriesDirectory();
        $filepath = $savePath . '/' . $filename . '.stories.json';

        try {
            FileHelper::writeToFile($filepath, Json::encode($storyData));
            echo 'Successfully wrote ' . $filepath;
        } catch (Exception $exception) {
            echo 'Failed to write ' . $filepath;
            echo $exception->getMessage();
        }
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
