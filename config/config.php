<?php

return [
    'component_class' => \Maxeckel\LivewireEditorjs\Http\Livewire\EditorJS::class,

    'component_name' => 'editorjs',

    // Defines on which disk images, uploaded through the editor, should be stored.
    'default_img_upload_disk' => 'public',

    // Defines on which disk images, downloaded by pasting an image url into the editor, should be stored.
    'default_img_download_disk' => 'public',
];
