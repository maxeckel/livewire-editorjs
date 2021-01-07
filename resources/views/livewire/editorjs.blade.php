<div x-data="editorInstance('data', '{{ $editorId }}', {{ $readOnly ? 'true' : 'false' }})" x-init="init()" class="{{ $class }}" style="{{ $style }}" wire:ignore>
    <div id="{{ $editorId }}"></div>
</div>
