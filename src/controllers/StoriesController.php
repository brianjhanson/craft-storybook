<?php
/**
 * craft-storybook-example module for Craft CMS 3.x
 *
 * @link      https://onedesigncompany.com
 * @copyright Copyright (c) 2022 Brian Hanson
 */

namespace brianjhanson\storybook\controllers;

use brianjhanson\storybook\Storybook;
use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\errors\SiteNotFoundException;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use craft\web\Controller;
use Exception;
use yii\web\Response;

/**
 * @author    Brian Hanson
 * @package   craft-storybook-example
 * @since     1.0.0
 */
class StoriesController extends Controller
{
    /**
     * @inheritdoc
     */
    protected int|bool|array $allowAnonymous = ['preview'];

    /**
     * @inheritDoc
     */
    public function beforeAction($action): bool
    {
        $this->response->getHeaders()
            ->add('Access-Control-Allow-Origin', '*')
            ->add('Access-Control-Allow-Credentials', 'true');

        return parent::beforeAction($action);
    }

    /**
     * Render a component preview
     *
     * @param string|null $component Component name
     * @return Response
     * @throws Exception
     */
    public function actionPreview(string $component = null): Response
    {
        $params = Craft::$app->request->getQueryParams();
        $vars = $this->normalizeParams($params);

        return $this->renderTemplate($component, $vars);
    }

    /**
     * @param string $value
     * @return bool|string|ElementInterface|null
     */
    private function normalizeValue(string $value): mixed
    {
        if (StringHelper::startsWith($value, '{%')) {
            return Storybook::getInstance()->stories->parseStoryRefs($value);
        }

        $value = Craft::$app->getElements()->parseRefs($value);

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        return $value;
    }

    /**
     * Normalize an array of params
     *
     * @param array $params
     * @return array
     */
    private function normalizeParams(array $params = []): array
    {
        $normalized = [];

        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $normalized[$key] = $this->normalizeParams($value);
            } else {
                $normalized[$key] = $this->normalizeValue($value);
            }
        }

        return $normalized;
    }
}
