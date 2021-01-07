<?php

namespace Maxeckel\LivewireEditorjs\Tests;

use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Livewire\Testing\TestableLivewire;
use Maxeckel\LivewireEditorjs\Http\Livewire\EditorJS;
use Maxeckel\LivewireEditorjs\LivewireEditorjsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            LivewireEditorjsServiceProvider::class
        ];
    }

    protected function createComponent($parameters = []) : TestableLivewire
    {
        return Livewire::test(EditorJS::class, $parameters);
    }
}
