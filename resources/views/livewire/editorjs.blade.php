<div x-data="editorInstance('data', '{{ $editorId }}', {{ $readOnly }})" x-init="init()" class="{{ $class }}" wire:ignore>
    <div id="{{ $editorId }}"></div>
</div>
