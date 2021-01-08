<?php

return [
    'enabled_component_registration' => true,

    'component_name' => 'editorjs',

    // Sets the default placeholder to use when a new and empty Editor.js instance is created.
    'default_placeholder' => '',

    /*
     * Available options:
     *
     * VERBOSE	Show all messages (default)
     * INFO	Show info and debug messages
     * WARN	Show only warn messages
     * ERROR	Show only error messages
     *
     * Taken from the offical docs of Editor.js:
     * https://editorjs.io/configuration#log-level
     */
    'editorjs_log_level' => 'ERROR',

    // Defines on which disk images, uploaded through the editor, should be stored.
    'default_img_upload_disk' => 'public',

    // Defines on which disk images, downloaded by pasting an image url into the editor, should be stored.
    'default_img_download_disk' => 'public',
];
