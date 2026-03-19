<?php

namespace Androsamp\FilamentRichEditorHighlight;

use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Facades\FilamentAsset;

class HighlightRichContentPlugin implements RichContentPlugin
{
    public function getId(): string
    {
        return 'highlightColorPicker';
    }

    public static function make(): static
    {
        return app (static::class);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    public function getTipTapJsExtensions(): array
    {
        $scriptSrc = FilamentAsset::getScriptSrc('rich-content-plugins/highlight');
        $relativeSrc = parse_url($scriptSrc, PHP_URL_PATH) ?: $scriptSrc;
        $query = parse_url($scriptSrc, PHP_URL_QUERY);

        return [
            filled($query) ? "{$relativeSrc}?{$query}" : $relativeSrc,
        ];
    }

    /**
     * @return array<RichEditorTool>
     */
    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make ('highlightColorPicker')
                ->label (__ ('filament-rich-editor-highlight::highlight.label'))
                ->icon ('heroicon-o-tag')
                ->action (arguments: '{ backgroundColor: $getEditor()?.getAttributes(\'highlight\')?.color ?? null }'),
        ];
    }

    public function getEditorActions(): array
    {
        return [
            Action::make ('highlightColorPicker')
                ->modalWidth ('lg')
                ->modalHeading (__ ('filament-rich-editor-highlight::highlight.modal_heading'))
                ->fillForm (fn(array $arguments): array => [
                    'backgroundColor' => $arguments['backgroundColor'] ?? null,
                ])
                ->schema ([
                    ColorPicker::make ('backgroundColor'),
                ])
                ->action (function (array $arguments, array $data, RichEditor $component): void {
                    $component->runCommands (
                        [
                            EditorCommand::make ('toggleHighlight', arguments: [
                                [
                                    'color' => $data['backgroundColor'] ?? null,
                                ]
                            ]),
                        ],
                        editorSelection: $arguments['editorSelection'] ?? null,
                    );
                }),
        ];
    }
}


