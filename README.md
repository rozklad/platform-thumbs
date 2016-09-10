# sanatorium/thumbs

Thumbnails for Cartalyst Platform

## Contents

1. [Documentation](#documentation)
2. [Changelog](#changelog)
3. [Support](#support)
4. [Hooks](#hooks)

## Documentation

config/platform-media.php
    
    'macros' => [
        ...
        'thumbs' => 'Sanatorium\Thumbs\Styles\Macros\ThumbsMacro',
    ],

    'presets' => [
        ...
        '300'    => [
            'width'  => 300,
            'macros' => ['thumbs'],
            'path'   => config('cartalyst.filesystem.connections.' . config('cartalyst.filesystem.default') . '.prefix') . '/cache/thumbs'
        ],
    ],


## Changelog

Changelog not available.

## Support

Support not available.

## Hooks

List of currently used hooks:

    'sample' => 'sanatorium/thumbs::hooks.sample'