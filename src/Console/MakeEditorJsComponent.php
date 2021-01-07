<?php

namespace Maxeckel\LivewireEditorjs\Console;

use Illuminate\Console\GeneratorCommand;

class MakeEditorJsComponent extends GeneratorCommand
{
    protected $name = 'make:editorjs';

    protected $description = 'Creates an Editor.JS livewire component in your project';

    protected $type = 'Editor.Js Component';

    protected function getStub()
    {
        return __DIR__.'/stubs/component.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Livewire';
    }
}
