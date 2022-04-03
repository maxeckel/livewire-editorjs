<?php

namespace Maxeckel\LivewireEditorjs\View\Components;

use Illuminate\View\Component;

class Editor extends Component
{
    public $placeholder;
    public $readonly;
    public $logLevel;

    public function __construct($placeholder = null, $readonly = false, $logLevel = null)
    {
        $this->placeholder = $placeholder ?? config('livewire-editorjs.default_placeholder');
        $this->readonly = $readonly;
        $this->logLevel = $logLevel ?? config('livewire-editorjs.log_level');
    }

    public function render()
    {
        return view('livewire-editorjs::components.editor');
    }
}
