<?php

namespace Maxeckel\LivewireEditorjs\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maxeckel\LivewireEditorjs\Tests\TestCase;

class UploadImageTest extends TestCase
{
    /** @test */
    public function can_upload_image()
    {
        Storage::fake('public');

        $component = $this->createComponent(['editorId' => 'testEditor']);

        $file = UploadedFile::fake()->image('dummy.png');

        $component->set('uploads', [$file]);

        $tmpFile = explode('"', $component->get('uploads'))[1];

        $component
            ->call('completedImageUpload', $tmpFile, 'test', $file->hashName())
            ->assertDispatchedBrowserEvent('test');

        Storage::disk('public')->assertExists($file->hashName());
    }

    /** @test */
    public function correct_disk_is_used_for_saving_image()
    {
        $diskName = 'testDisk';

        Storage::fake($diskName);

        $component = $this->createComponent([
            'editorId' => 'testEditor',
            'uploadDisk' => $diskName,
        ]);

        $file = UploadedFile::fake()->image('dummy.png');

        $component->set('uploads', [$file]);

        $tmpFile = explode('"', $component->get('uploads'))[1];

        $component
            ->call('completedImageUpload', $tmpFile, 'test', $file->hashName())
            ->assertDispatchedBrowserEvent('test');

        Storage::disk($diskName)->assertExists($file->hashName());
    }
}
