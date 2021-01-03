# Easy integration of Editor.js in Laravel Livewire

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maxeckel/livewire-editorjs.svg?style=flat-square)](https://packagist.org/packages/maxeckel/livewire-editorjs)
[![Build Status](https://img.shields.io/travis/maxeckel/livewire-editorjs/master.svg?style=flat-square)](https://travis-ci.org/maxeckel/livewire-editorjs)
[![Quality Score](https://img.shields.io/scrutinizer/g/maxeckel/livewire-editorjs.svg?style=flat-square)](https://scrutinizer-ci.com/g/maxeckel/livewire-editorjs)
[![Total Downloads](https://img.shields.io/packagist/dt/maxeckel/livewire-editorjs.svg?style=flat-square)](https://packagist.org/packages/maxeckel/livewire-editorjs)

This Package adds a Livewire component to your application, which will create an Editor.js instance for you.

Out of the box it already supports image uploads using Livewire and the Image plugin of Editor.js.

## Requirements

This package requires you to have the following tools installed:

- [Laravel Livewire v2](https://laravel-livewire.com/docs/2.x/quickstart)
- [Alpine.JS v2](https://github.com/alpinejs/alpine)

Please refer to the linked guides on how to install these.

## Installation

You can install the package via composer:

```bash
composer require maxeckel/livewire-editorjs
```

After composer downloaded the package, you will need to publish it's config & assets:

#### Config (optional)

```bash
php artisan vendor:publish --tag=livewire-editorjs:config
```

#### Assets

For the assets you have two options:

1. Already, for production, compiled assets including the above mentioned plugins:
```bash
php artisan vendor:publish --tag=livewire-editorjs:assets:compiled
```

This will copy the compiled assets into `public/vendor/livewire-editorjs`.

2. Publish the raw assets to include them in your own build chain:
```bash
php artisan vendor:publish --tag=livewire-editorjs:assets:raw
```

This will copy the raw assets into `resources/js/vendor/livewire-editorjs`.  
Now you have to include these assets into your own build chain.  

This method will be needed, if you want to further customize the editor, e.g. adding more plugins or  
configure the installed ones in a different way.

#### IMPORTANT

**For this to work, you will need to install all the plugins you want to use yourself!**  
**This also includes the ones already configured!**


The last step is to include the scripts within your views. You can do this how ever you prefer.
If you have chosen option 1. you can include the scripts with a little blade directive:  

`@livewireEditorjsScripts`

**Advice**

If you opted for option 1. of publishing the assets, you should make sure, that after a `composer update` 
the assets are published again to avoid them being outdated. In order to do so update your `composer.json` script 
configuration:

```json
{
    "scripts": {
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi",
            "@php artisan vendor:publish --tag=livewire-editorjs:assets:compiled"
        ]
    }
}
```

If you opted for option 2. I wouldn't suggest to automatically republish the raw assets, as this would overwrite any 
changes you made. Please check the changelog after you updated this package.

## Packaged Editor.js plugins

- [Code](https://github.com/editor-js/code)
- [Header](https://github.com/editor-js/header)
- [Image](https://github.com/editor-js/image) Configured with Livewire Upload out of the box
- [Inline Code](https://github.com/editor-js/inline-code)
- [List](https://github.com/editor-js/list)
- [Quote](https://github.com/editor-js/quote)
- [Underline](https://github.com/editor-js/underline)

## Usage

``` php
@livewire('editorjs', [
    'editor-id' => "myEditor",
    'value' => $value,
    'uploadDisk' => 'public',
    'downloadDisk' => 'public',
    'class' => '...'
])
```

``` php
<livewire:editorjs
   editor-id="myEditor"
   :value="$value" 
   upload-disk="public" 
   download-disk="public" 
   class="..." 
/>
```

### Properties / Parameters

#### editorId

The `editorId` parameter is used to generate a unique events from the Livewire component, 
in order for you to be able to listen for events of specific editors (in case more than 1 is used on the same page)

#### value

The `value` parameter sets the inital data for the editor instance.  
This would be your stored JSON data of Editor.js

#### uploadDisk (optional)

The `uploadDisk` parameter defines the disk, to which uploaded images should be stored.  
This parameter is optional. The default disk to store images in is defined within the packages config file:

`config/livewire-editorjs.php` => `default_upload_img_disk`

#### downloadDisk (optional)

The `downloadDisk` parameter defines the disk, to which downloaded images should be stored. 
Images will be downloaded from the internet when a user pasts an image URL into the Editor (see [Editor.js image plugin](https://github.com/editor-js/image))  
This parameter is optional. The default disk to store images in is defined within the packages config file:

`config/livewire-editorjs.php` => `default_download_img_disk`

#### class

As the name suggests you can pass in CSS classes as you would with any other component.  
For styling the Editor/Blocks, please refer to the [documentation](https://editorjs.io/styles) of Editor.js

### Events / Saving state

The Editor is configured to save changes to the server using its built in `onChange` callback.  
In this callback the editor will sync its state with Livewire.  
When this happens, Livewire will `emitUp` a save event:  

`editorjs-save:editorId`

The editorId is substituted, with the editorId parameter you passed to the component.  
With this, you can listen for the event within your Livewire Page/Component you use the editor in,
in order to save changes to your models.

```php 
protected $listeners = ['editorjs-save:editorId' => 'saveEditorState'];

public function saveEditorState($editorJsonData)
{
    $this->model->data = $editorJsonData;
}
```


### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email max.eckel@outlook.de instead of using the issue tracker.

## Credits

- [Max Eckel](https://github.com/maxeckel)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
