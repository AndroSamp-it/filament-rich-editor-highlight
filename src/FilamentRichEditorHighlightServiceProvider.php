<?php

namespace Androsamp\FilamentRichEditorHighlight;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class FilamentRichEditorHighlightServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\Tiptap\Marks\Highlight::class, PlainHighlightMark::class);
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-rich-editor-highlight');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/filament-rich-editor-highlight'),
        ], 'filament-rich-editor-highlight-translations');

        FilamentAsset::register([
            Js::make('rich-content-plugins/highlight', __DIR__ . '/../resources/dist/filament/rich-content-plugins/highlight.js')->loadedOnRequest(),
        ]);

        RichEditor::configureUsing(function (RichEditor $richEditor) {
            $richEditor->plugins([
                HighlightRichContentPlugin::make(),
            ]);
        });

        $this->app->resolving(RichContentRenderer::class, function (RichContentRenderer $renderer) {
            $alreadyRegistered = array_any(
                $renderer->getPlugins(),
                fn ($plugin) => $plugin->getId() === 'highlightColorPicker',
            );

            if (! $alreadyRegistered) {
                $renderer->plugins([HighlightRichContentPlugin::make()]);
            }
        });
    }
}


