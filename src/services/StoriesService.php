<?php

namespace brianjhanson\storybook\services;

use Craft;
use craft\base\Component;
use craft\base\ElementInterface;
use craft\helpers\StringHelper;

class StoriesService extends Component
{
    /**
     * @param string $str
     * @return ElementInterface|null
     */
    public function parseStoryRefs(string $str): ElementInterface|null
    {
        $elementService = Craft::$app->getElements();
        $core = StringHelper::trim($str, '{%}');
        $parts = array_pad(explode(':', $core), 2, null);

        $refHandle = $parts[0];
        $ref = $parts[1];
        $elementType = $elementService->getElementTypeByRefHandle($refHandle);

        $elementQuery = $elementService->createElementQuery($elementType)
            ->status(null);

        if ($ref) {
            $elementQuery->id($ref);
        }

        return $elementQuery->one();
    }
}