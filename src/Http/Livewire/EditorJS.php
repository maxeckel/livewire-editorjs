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

    public bool $readOnly;

    public $uploadDisk;

    public $downloadDisk;

    public function mount($editorId, $value = [], $class = '', $uploadDisk = null, $downloadDisk = null, $readOnly = false)
    {
        if (is_null($uploadDisk)) {
            $uploadDisk = config('livewire-editorjs.default_img_upload_disk');
        }

        if (is_null($downloadDisk)) {
            $downloadDisk = config('livewire-editorjs.default_img_download_disk');
        }

        $this->editorId = $editorId;
        $this->data = $value;
        $this->class = $class;
        $this->uploadDisk = $uploadDisk;
        $this->downloadDisk = $downloadDisk;
        $this->readOnly = $readOnly;
    }

    public function completedImageUpload(string $uploadedFileName, string $eventName)
    {
        /** @var TemporaryUploadedFile $tmpFile */
        $tmpFile = collect($this->uploads)
            ->filter(fn (TemporaryUploadedFile $item) => $item->getFilename() === $uploadedFileName)
            ->first();

        $storedFileName = $tmpFile->store('/', $this->uploadDisk);

        $this->dispatchBrowserEvent($eventName, [
            'url' => Storage::disk($this->uploadDisk)->url($storedFileName),
        ]);
    }

    public function loadImageFromUrl(string $url)
    {
        $name = basename($url);
        $content = file_get_contents($url);

        Storage::disk($this->downloadDisk)->put($name, $content);

        return Storage::disk($this->downloadDisk)->url($name);
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
