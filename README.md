# Easy integration of Editor.js in Laravel Livewire

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maxeckel/livewire-editorjs.svg?style=for-the-badge)](https://packagist.org/packages/maxeckel/livewire-editorjs)
[![Total Downloads](https://img.shields.io/packagist/dt/maxeckel/livewire-editorjs.svg?style=for-the-badge)](https://packagist.org/packages/maxeckel/livewire-editorjs)
![GitHub](https://img.shields.io/github/license/maxeckel/livewire-editorjs?style=for-the-badge)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/maxeckel/livewire-editorjs/run-tests?style=for-the-badge)

This Package adds a Livewire component to your application, which will create an Editor.js instance for you.

Out of the box it already supports image uploads using Livewire and the Image plugin of Editor.js.

**Packaged version of Editor.js: 2.22.3**

## Requirements

This package requires you to have the following tools installed:

- [Laravel Livewire v2](https://laravel-livewire.com/docs/2.x/quickstart)
- [Alpine.JS v2 or v3](https://alpinejs.dev/)

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
php artisan vendor:publish --tag=livewire-editorjs:assets:compiled --force
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
            "@php artisan vendor:publish --tag=livewire-editorjs:assets:compiled --force"
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
    'editorId' => "myEditor",
    'value' => $value,
    'uploadDisk' => 'public',
    'downloadDisk' => 'public',
    'class' => '...',
    'style' => '...',
    'readOnly' => true,
    'placeholder' => 'Lorem ipsum dolor sit amet'
])
```

``` php
<livewire:editorjs
   editor-id="myEditor"
   :value="$value" 
   upload-disk="public" 
   download-disk="public" 
   class="..." 
   style="..."
   :read-only="true"
   placeholder="Lorem ipsum dolor sit amet"
/>
```

### Properties / Parameters

#### editorId

The `editorId` parameter is used to generate a unique events from the Livewire component, 
in order for you to be able to listen for events of specific editors (in case more than 1 is used on the same page)

**Important!**  
**Don't use the passed id anywhere else as `id` attribute on an HTML element, as the `editorId` is internally used
as `id` on the wrapper `div` in which Editor.js gets initialized!  
If you use the `id` somewhere else, the instance will break!**  

#### value

The `value` parameter sets the inital data for the editor instance.  
This would be your stored JSON data of Editor.js

#### uploadDisk (optional)

The `uploadDisk` parameter defines the disk, to which uploaded images should be stored.  
This parameter is optional. The default disk to store images in is defined within the packages config file:

`config/livewire-editorjs.php` => `default_upload_img_disk`

Default: `public`

#### downloadDisk (optional)

The `downloadDisk` parameter defines the disk, to which downloaded images should be stored. 
Images will be downloaded from the internet when a user pasts an image URL into the Editor (see [Editor.js image plugin](https://github.com/editor-js/image))  
This parameter is optional. The default disk to store images in is defined within the packages config file:

`config/livewire-editorjs.php` => `default_download_img_disk`

Default: `public

#### class (optional)

As the name suggests you can pass in CSS classes as you would with any other component.  
For styling the Editor/Blocks, please refer to the [documentation](https://editorjs.io/styles) of Editor.js

Default: ""

#### style (optional)

As the name suggest you can pass in inline styles as you would with any other component.

Default: ""

#### readOnly (optional)

You can pass this parameter with an value of "true" to set the editor into read only mode.
This might be useful, if you want to display articles the same way, as they have been created.

Default: `false`

#### placeholder (optional)

Using the `placeholder` property, you can pass a placeholder to the Editor.js instance, which will
be displayed in an empty editor.

Default: '' (set through the corresponding config option `default_placeholder`)

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

### Config

In order to change the config, you'll first need to publish it:

`php artisan vendor:publish --provider="Maxeckel\LivewireEditorjs\LivewireEditorjsServiceProvider" --tag="livewire-editorjs:config"`

or  

`php artisan vendor:publish` and select `livewire-editorjs:config` by entering its number.

#### Default config

```php
<?php

return [
    'enabled_component_registration' => true,

    'component_name' => 'editorjs',

    // Sets the default placeholder to use when a new and empty Editor.js instance is created.
    'default_placeholder' => '',

    /*
     * Available options:
     *
     * VERBOSE	Show all messages (default)
     * INFO	Show info and debug messages
     * WARN	Show only warn messages
     * ERROR	Show only error messages
     *
     * Taken from the offical docs of Editor.js:
     * https://editorjs.io/configuration#log-level
     */
    'editorjs_log_level' => 'ERROR',

    // Defines on which disk images, uploaded through the editor, should be stored.
    'default_img_upload_disk' => 'public',

    // Defines on which disk images, downloaded by pasting an image url into the editor, should be stored.
    'default_img_download_disk' => 'public',
];

```

##### enabled_component_registration (default: `true`)

This option defines, whether the ServiceProvider should register the default livewire component while booting.  
Set this to `false` when you want to disable the internal component and use your own using the `make:editorjs` command.

##### component_name (default: `editorjs`)

This option defines, under which name the internal component should be registered.  
By default this is set to `editorjs`, making the component accessible via "<livewire:editorjs>" or "@livewire('editorjs')".   
You can change this to whatever fits you best!

##### default_placeholder (default: `''`)

This option sets a global default for the placeholder property of Editor.js.  
The placeholder will be displayed when an instance is created without any content.

##### editorjs_log_level (default: `'ERROR'`)

This option sets the log level (console output) of Editor.js.  
The available options are:

| Value         | Definition                         |
| ------------- | ---------------------------------- |
| VERBOSE       | Show all messages                  |
| INFO          | Show info and debug messages       |
| WARN          | Show only warn messages            |
| ERROR         | Show only error messages (default) |

See [Editor.js docs](https://editorjs.io/configuration#log-level) for reference.

##### default_img_upload_disk (default: `public`)

This option defines, to which disk uploaded images should be stored.  
Even though the disk can be changed on a per instance level, this option lets you set a global default.  
This is always used, when you don't provide a disk name to the component instance through its props.

##### default_img_download_disk (default: `public`)

This option defines, to which disk downloaded images from the web should be stored.  
Even though the disk can be changed on a per instance level, this option lets you set a global default.  
This is always used, when you don't provide a disk name to the component instance through its props.

### Commands

This package adds an `make:editorjs` command to your project.
With this command it's possible for you to create your own EditorJs livewire component.
This makes it possible for you to change and/or customize the component.

If you add your own component this way, you should disable the packages internal component registration
by setting `enabled_component_registration` in the `livewire-editorjs.php` config file to `false`.

**Important!**  
**By using this method to create your own component, any updates to the packages component won't affect you!**  
**Which means any enhancements made won't be accessible to you.**

### Extending

If you want to customize the component or extend its functionality, the best way is to extend the component
provided by this package. That way, you will receive updates and still can customize the internals.

```php
<?php

namespace App\Http\Livewire;

use Maxeckel\LivewireEditorjs\Http\Livewire\EditorJS;

class MyCustomEditor extends EditorJS
{
    // Put your custom code here
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

If you discover any security related issues, please email security@max-eckel.dev instead of using the issue tracker.

## Credits

- [Max Eckel](https://github.com/maxeckel)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
