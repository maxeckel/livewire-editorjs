<?php

namespace Maxeckel\LivewireEditorjs\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Maxeckel\LivewireEditorjs\Tests\TestCase;

class MakeEditorJsComponentTest extends TestCase
{
    /** @test */
    function it_creates_a_new_editorjs_component()
    {
        $componentClass = app_path('Http/Livewire/TestEditorJs.php');

        if (File::exists($componentClass)) {
            unlink($componentClass);
        }

        $this->assertFalse(File::exists($componentClass));

        Artisan::call('make:editorjs TestEditorJs');

        $this->assertTrue(File::exists($componentClass));
    }
}
