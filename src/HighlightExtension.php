<?php

namespace Androsamp\FilamentRichEditorHighlight;

use Tiptap\Marks\Highlight;
use Tiptap\Utils\HTML;
use Tiptap\Utils\InlineStyle;

/**
 * Custom Highlight extension with correct color handling
 */
class HighlightExtension extends Highlight
{
    public static $name = 'highlightColorPicker';

    /**
     * Only match <mark data-color="..."> to avoid conflict with the built-in
     * Highlight extension (name='highlight') that already handles plain <mark>.
     */
    public function parseHTML(): array
    {
        return [
            ['tag' => 'mark[data-color]'],
        ];
    }

    public function addAttributes()
    {
        if (! $this->options['multicolor']) {
            return [];
        }

        return [
            'color' => [
                'default' => null,
                'parseHTML' => function ($DOMNode) {
                    if ($color = $DOMNode->getAttribute('data-color')) {
                        return $color;
                    }

                    return InlineStyle::getAttribute($DOMNode, 'background-color') ?: null;
                },
                'renderHTML' => function ($attributes) {
                    if (empty($attributes->color ?? null)) {
                        return null;
                    }

                    return [
                        'data-color' => $attributes->color,
                        'style' => "background-color: {$attributes->color}",
                    ];
                },
            ],
        ];
    }

    public function renderHTML($mark, $HTMLAttributes = [])
    {
        $mergedAttributes = HTML::mergeAttributes($this->options['HTMLAttributes'], $HTMLAttributes);
        $styles = $this->parseStyleAttribute((string) ($mergedAttributes['style'] ?? ''));

        if (filled($mergedAttributes['data-color'] ?? null)) {
            $styles['background-color'] = (string) $mergedAttributes['data-color'];
        }

        if ($styles === []) {
            unset($mergedAttributes['style']);
        } else {
            $mergedAttributes['style'] = $this->stringifyStyleAttribute($styles);
        }

        return [
            'mark',
            $mergedAttributes,
            0,
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function parseStyleAttribute(string $style): array
    {
        $styles = [];

        foreach (explode(';', $style) as $declaration) {
            $declaration = trim($declaration);

            if ($declaration === '') {
                continue;
            }

            [$property, $value] = array_pad(explode(':', $declaration, 2), 2, null);

            if (blank($property) || blank($value)) {
                continue;
            }

            $styles[strtolower(trim($property))] = trim($value);
        }

        return $styles;
    }

    /**
     * @param  array<string, string>  $styles
     */
    protected function stringifyStyleAttribute(array $styles): string
    {
        return implode('; ', array_map(
            fn (string $property, string $value): string => "{$property}: {$value}",
            array_keys($styles),
            $styles,
        ));
    }
}
