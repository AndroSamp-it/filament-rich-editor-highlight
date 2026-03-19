# filament-rich-editor-highlight

[![Latest Version on Packagist](https://img.shields.io/packagist/v/androsamp/filament-rich-editor-highlight.svg?style=flat-square)](https://packagist.org/packages/androsamp/filament-rich-editor-highlight)
[![Total Downloads](https://img.shields.io/packagist/dt/androsamp/filament-rich-editor-highlight.svg?style=flat-square)](https://packagist.org/packages/androsamp/filament-rich-editor-highlight)

A Filament Rich Editor plugin that adds text highlight color selection (`<mark data-color="...">`) powered by TipTap.

## Features

- Adds the `highlightColorPicker` tool to the editor toolbar.
- Opens a modal with a `ColorPicker` to choose highlight color.
- Registers a TipTap JS extension with multicolor highlight support.
- Renders highlighted content correctly through `RichContentRenderer`.

## Requirements

- PHP `^8.1`
- `filament/forms` `^4.0|^5.0`

## Installation

```bash
composer require androsamp/filament-rich-editor-highlight
```

If you are developing the package locally:

```bash
cd packages/custom-rich-editor-highlight
npm install
npm run build
```

## Usage

After installation, the package service provider automatically registers the plugin for `RichEditor`.

To show the button in the UI, include `highlightColorPicker` in your toolbar configuration:

```php
use Filament\Forms\Components\RichEditor;

RichEditor::make('content')
    ->toolbarButtons([
        'bold',
        'italic',
        'highlightColorPicker',
    ]);
```

For `floatingToolbars`:

```php
RichEditor::make('content')
    ->floatingToolbars([
        'paragraph' => [
            'highlightColorPicker',
        ],
    ]);
```

## Content Rendering

The plugin is also automatically registered for `RichContentRenderer`, so highlighted HTML is rendered correctly:

```php
use Filament\Forms\Components\RichEditor\RichContentRenderer;

$html = RichContentRenderer::make($post->content)->toHtml();
```

## Localization

The package includes translations for the button label and modal heading.

Available keys:

```php
'filament-rich-editor-highlight::highlight.label'
'filament-rich-editor-highlight::highlight.modal_heading'
```

Publish translations:

```bash
php artisan vendor:publish --tag=filament-rich-editor-highlight-translations
```

Published files path: `resources/lang/vendor/filament-rich-editor-highlight`.

## Build and Assets

After changing the JS part of the plugin:

```bash
cd packages/custom-rich-editor-highlight
npm run build
```

If you need to publish Filament assets:

```bash
php artisan filament:assets
```

## License

MIT
