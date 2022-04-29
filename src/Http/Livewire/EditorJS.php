<?php

namespace Maxeckel\LivewireEditorjs\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class EditorJS extends Component
{
    use WithFileUploads;

    public $uploads = [];

    public $editorId;

    public $data;

    public $class;

    public $style;

    public $readOnly;

    public $placeholder;

    public $uploadDisk;

    public $downloadDisk;

    public $prefixPath;

    public $logLevel;

    public function mount(
        $editorId,
        $value = [],
        $class = '',
        $style = '',
        $readOnly = false,
        $placeholder = null,
        $uploadDisk = null,
        $downloadDisk = null,
        $prefixPath = null
    ) {
        if (is_null($uploadDisk)) {
            $uploadDisk = config('livewire-editorjs.default_img_upload_disk');
        }

        if (is_null($downloadDisk)) {
            $downloadDisk = config('livewire-editorjs.default_img_download_disk');
        }

        if (is_null($placeholder)) {
            $placeholder = config('livewire-editorjs.default_placeholder');
        }

        if (is_null($prefixPath)) {
            $prefixPath = config('livewire-editorjs.upload_path');
        }

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        $this->editorId = $editorId;
        $this->data = $value;
        $this->class = $class;
        $this->style = $style;
        $this->readOnly = $readOnly;
        $this->placeholder = $placeholder;
        $this->uploadDisk = $uploadDisk;
        $this->downloadDisk = $downloadDisk;
        $this->prefixPath = $prefixPath;

        $this->logLevel = config('livewire-editorjs.editorjs_log_level');
    }

    public function completedImageUpload(string $uploadedFileName, string $eventName, $fileName = null)
    {
        /** @var TemporaryUploadedFile $tmpFile */
        $tmpFile = collect($this->uploads)
            ->filter(function (TemporaryUploadedFile $item) use ($uploadedFileName) {
                return $item->getFilename() === $uploadedFileName;
            })
            ->first();

        // When no file name is passed, we use the hashName of the tmp file
        $storedFileName = $tmpFile->storeAs(
            $this->prefixPath ?? '/',
            $fileName ?? $tmpFile->hashName(),
            $this->uploadDisk
        );

        if (config("filesystems.disks.$this->uploadDisk.driver") == 's3' &&
            config("filesystems.disks.$this->uploadDisk.visibility", 'private') == 'private') {
            $this->dispatchBrowserEvent($eventName, [
                'url' => Storage::disk($this->uploadDisk)->temporaryUrl(
                    $storedFileName,
                    now()->addSeconds(config('livewire-editorjs.temporary_url_timeout', 86400))
                ),
            ]);
        } else {
            $this->dispatchBrowserEvent($eventName, [
                'url' => Storage::disk($this->uploadDisk)->url($storedFileName),
            ]);
        }
    }

    public function loadImageFromUrl(string $url)
    {
        $name = basename($url);
        $content = file_get_contents($url);

        $storedFileName = Storage::disk($this->downloadDisk)->putFileAs($this->prefixPath ?? '/', $name, $content);

        if (config("filesystems.disks.$this->uploadDisk.driver") == 's3' &&
            config("filesystems.disks.$this->uploadDisk.visibility", 'private') == 'private') {
            return Storage::disk($this->downloadDisk)->temporaryUrl(
                $storedFileName,
                now()->addSeconds(config('livewire-editorjs.temporary_url_timeout', 86400))
            );
        }

        return Storage::disk($this->downloadDisk)->url($storedFileName);
    }

    public function save()
    {
        $this->emitUp("editorjs-save:{$this->editorId}", $this->data);
    }

    public function render()
    {
        return view('livewire-editorjs::livewire.editorjs');
    }
}
