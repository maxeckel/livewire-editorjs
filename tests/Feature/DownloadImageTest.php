<?php

namespace Maxeckel\LivewireEditorjs\Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Maxeckel\LivewireEditorjs\Tests\TestCase;

class DownloadImageTest extends TestCase
{
    /** @test */
    public function can_download_image()
    {
        Storage::fake('public');

        $component = $this->createComponent(['editorId' => 'testEditor']);

        $component
            ->call('loadImageFromUrl', 'https://dummyimage.com/300/09f/fff.png');

        Storage::disk('public')->assertExists('fff.png');
    }

    /** @test */
    public function correct_disk_is_used_for_saving_image()
    {
        $diskName = 'testDisk';

        Storage::fake($diskName);

        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'downloadDisk' => $diskName
        ]);

        $component
            ->call('loadImageFromUrl', 'https://dummyimage.com/300/09f/fff.png');

        Storage::disk($diskName)->assertExists('fff.png');
    }
}
