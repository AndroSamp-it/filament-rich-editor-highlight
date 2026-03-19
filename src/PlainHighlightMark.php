<?php

namespace Androsamp\FilamentRichEditorHighlight;

use Tiptap\Marks\Highlight;

class PlainHighlightMark extends Highlight
{
    public function addOptions()
    {
        return [
            ...parent::addOptions(),
            'multicolor' => true,
        ];
    }

    public function parseHTML(): array
    {
        return [
            [
                'tag' => 'mark',
            ],
        ];
    }
}
