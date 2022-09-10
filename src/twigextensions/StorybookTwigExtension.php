<?php

namespace brianjhanson\storybook\twigextensions;

use brianjhanson\storybook\services\Story;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StorybookTwigExtension extends AbstractExtension
{
    /**
     * Return the extension's name.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Storybook';
    }

    /**
     * @inerhitdoc
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('story', [Story::class, 'story'], [
                'needs_context' => true
            ])
        ];
    }
}
