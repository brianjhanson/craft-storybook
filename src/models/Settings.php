<?php

namespace brianjhanson\storybook\models;

use craft\base\Model;
use craft\helpers\App;

class Settings extends Model
{
    /**
     * Directory where your stories live
     *
     * @var string;
     */
    public $storiesDirectory;

    /**
     * Return the parsed stories directory
     *
     * @return bool|string|null
     */
    public function getStoriesDirectory(): bool|string|null
    {
        return App::parseEnv($this->storiesDirectory);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['storiesDirectory', 'required']
        ];
    }
}
