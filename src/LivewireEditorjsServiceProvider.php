<?php

namespace Maxeckel\LivewireEditorjs;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;
use Maxeckel\LivewireEditorjs\Console\MakeEditorJsComponent;
use Maxeckel\LivewireEditorjs\Http\Livewire\EditorJS;

class LivewireEditorjsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-editorjs');

        $this->registerPublishables();
        $this->registerDirectives();
        $this->registerCommands();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'livewire-editorjs');

        // Add Livewire component
        $this->app->afterResolving(BladeCompiler::class, function () {
            $enabledComponentRegistration = config('livewire-editorjs.enabled_component_registration', true);

            if (class_exists(Livewire::class) && $enabledComponentRegistration) {
                $componentName = config('livewire-editorjs.component_name', 'editorjs');

                Livewire::component($componentName, EditorJS::class);
            }
        });

        // Register the main class to use with the facade
        $this->app->singleton('livewire-editorjs', function () {
            return new LivewireEditorjs;
        });
    }

    private function registerPublishables()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('livewire-editorjs.php'),
        ], 'livewire-editorjs:config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-editorjs'),
        ], 'livewire-editorjs:views');

        // Publishing JS assets.
        $this->publishes([
            __DIR__.'/../resources/js' => resource_path('js/vendor/livewire-editorjs'),
        ], 'livewire-editorjs:assets:raw');

        // Publishing compiled JS assets.
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/livewire-editorjs'),
        ], 'livewire-editorjs:assets:compiled');
    }

    private function registerDirectives()
    {
        Blade::directive('livewireEditorjsScripts', function () {
            $scriptsUrl = asset('/vendor/livewire-editorjs/editorjs.js');

            return <<<EOF
                <script src="$scriptsUrl"></script>
            EOF;
        });
    }

    private function registerCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            MakeEditorJsComponent::class,
        ]);
    }
}
