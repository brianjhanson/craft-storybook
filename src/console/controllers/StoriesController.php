<?php


namespace brianjhanson\storybook\console\controllers;


use brianjhanson\storybook\services\Story;
use craft\console\Controller;
use yii\console\ExitCode;

/**
 * Interact with Storybook stories
 */
class StoriesController extends Controller
{

    /**
     * Generate JSON stories for twig stories
     *
     * @return int
     */
    public function actionGenerate($componentName): int
    {
        Story::generate($componentName);
        return ExitCode::OK;
    }

}
