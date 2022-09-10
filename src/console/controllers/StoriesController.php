<?php


namespace brianjhanson\storybook\console\controllers;


use Craft;
use craft\console\Controller;
use craft\helpers\App;
use craft\web\View;
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
    public function actionGenerate($componentName = '*'): int
    {
        $templateName = \craft\helpers\StringHelper::replace($componentName, App::parseEnv('@templates') . '/', '');
        echo $templateName;
        Craft::$app->getView()->renderTemplate($templateName, [], View::TEMPLATE_MODE_SITE);
        return ExitCode::OK;
    }

}
