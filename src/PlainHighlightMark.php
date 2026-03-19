<?php

namespace Androsamp\FilamentRichEditorHighlight;

use Tiptap\Marks\Highlight;

/**
 * Replaces the built-in Highlight mark so that it only matches plain <mark>
 * elements (no data-color attribute). Marks with data-color are handled
 * exclusively by HighlightExtension (name='highlightColorPicker').
 */
class PlainHighlightMark extends Highlight
{
    public function parseHTML(): array
    {
        return [
            [
                'tag'      => 'mark',
                'getAttrs' => fn ($DOMNode) => $DOMNode->hasAttribute('data-color') ? false : [],
            ],
        ];
    }
}
