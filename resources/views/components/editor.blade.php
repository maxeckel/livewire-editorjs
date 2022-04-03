<div
    x-data="editorjs('{{ $placeholder }}', @js($readonly), '{{$logLevel}}')"
    x-modelable="data"
    {{ $attributes }}
    wire:ignore
>
    <div
        :id="id"
    >
    </div>
</div>

