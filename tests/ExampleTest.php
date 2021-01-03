<?php

namespace Maxeckel\LivewireEditorjs\Tests;

use Maxeckel\LivewireEditorjs\LivewireEditorjsServiceProvider;
use Orchestra\Testbench\TestCase;

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
