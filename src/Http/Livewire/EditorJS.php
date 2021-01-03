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

    public $data;

    public $class;

    public $uploadDisk;

    public $downloadDisk;

    public function mount($value = [], $class = "", $uploadDisk = null, $downloadDisk = null)
    {
        if (is_null($uploadDisk)) {
            $uploadDisk = config('livewire-editorjs.default_img_upload_disk');
        }

        if (is_null($downloadDisk)) {
            $downloadDisk = config('livewire-editorjs.default_img_download_disk');
        }

        $this->data = $value;
        $this->class = $class;
        $this->uploadDisk = $uploadDisk;
        $this->downloadDisk = $downloadDisk;
    }

    public function completedUpload(string $uploadedFileName, string $eventName)
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
        $this->emitUp('editorjs-save', $this->data);
    }

    public function render()
    {
        return view('livewire-editorjs::livewire.editorjs');
    }
}
