<?php

namespace Maxeckel\LivewireEditorjs\Tests;

use Orchestra\Testbench\TestCase;
use Maxeckel\LivewireEditorjs\LivewireEditorjsServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LivewireEditorjsServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
