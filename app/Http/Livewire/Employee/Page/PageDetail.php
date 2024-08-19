<?php

namespace App\Http\Livewire\Employee\Page;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PageDetail extends Component
{
    use WithFileUploads;

    public Page $page;

    public $pageStatus;

    public $mediaFile;

    public $showMediaModal = false;

    public $selectedMedia;

    public $maxUploadSize = 100000; // 100MB

    protected $listeners = ['refresh' => '$refresh'];

    protected function rules()
    {
        return [
            'page.title' => 'required',
            'page.content' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->page->load('media');

        $this->pageStatus = $this->page->published ? 'published' : 'hidden';
    }

    public function updatedPageStatus($value)
    {
        if ($value === 'published') {
            $this->page->published_at = now();
        } else {
            $this->page->published_at = null;
        }
    }

    public function save()
    {
        $this->validate();

        $this->page->save();

        $this->emit('refresh')->self();

        $this->notify('Page saved!');
    }

    public function removePage()
    {
        $this->page->delete();

        $this->redirect(route('employee.pages.list'));
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function updatedMediaFile()
    {
        $mimeType = $this->mediaFile->getMimeType();

        if (str_contains($mimeType, 'image')) {
            $this->validate([
                'mediaFile' => 'required|image|max:' . $this->maxUploadSize,
            ]);
        } elseif (str_contains($mimeType, 'video')) {
            $this->validate([
                'mediaFile' => 'required|mimetypes:video/mp4,video/quicktime|max:' . $this->maxUploadSize,
            ]);
        } else {
            $this->reset('mediaFile');

            $this->addError('mediaFile', 'Invalid file type. Please upload an image or video file.');

            return;
        }

        $this->uploadMedia();
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function uploadMedia()
    {
        $this->page
            ->addMedia($this->mediaFile->getRealPath())
            ->usingName(pathinfo($this->mediaFile->getClientOriginalName(), PATHINFO_FILENAME))
            ->usingFileName($this->mediaFile->getClientOriginalName())
            ->toMediaCollection('media');

        $this->page->load('media');

        $this->reset('mediaFile');
    }

    public function insertMedia(Media $media)
    {
        $this->dispatchBrowserEvent('tiptap-insert-media', [
            'type' => $media->mime_type,
            'url' => '/' . $media->getPathRelativeToRoot(),
            'alt' => $media->name,
        ]);

        $this->showMediaModal = false;
    }

    public function deleteMedia(Media $media)
    {
        $media->delete();

        $this->page->load('media');
    }

    public function getMediaProperty()
    {
        return $this->page->media;
    }

    public function render()
    {
        return view('livewire.employee.page.page-detail')->layout('layouts.admin');
    }
}
