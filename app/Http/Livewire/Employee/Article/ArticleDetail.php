<?php

namespace App\Http\Livewire\Employee\Article;

use App\Models\Article;
use App\Models\Employee;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ArticleDetail extends Component
{
    use WithFileUploads;

    public Article $article;

    public $articleStatus;

    public $articleHasExcerpt = false;

    public $showMediaModal = false;

    public $mediaFile;

    public $selectedMedia;

    public $maxUploadSize = 100000; // 100MB

    public $featuredImage;

    public $featuredImageAlt = '';

    public $editingFeaturedImage = false;

    public $featuredImageBeingUpdated;

    protected $listeners = ['refresh' => '$refresh'];

    protected function rules()
    {
        return [
            'article.title' => 'required',
            'article.content' => 'nullable',
            'article.excerpt' => 'nullable',
        ];
    }

    public function mount()
    {
        $this->article->load('author', 'media', 'tags');

        $this->articleStatus = $this->article->published ? 'published' : 'hidden';

        $this->articleHasExcerpt = $this->article->excerpt !== null;
    }

    public function updatedArticleStatus($value)
    {
        if ($value === 'published') {
            $this->article->published_at = now();
        } else {
            $this->article->published_at = null;
        }
    }

    public function editFeaturedImage()
    {
        $this->featuredImageBeingUpdated = $this->article->getFirstMedia('cover');

        $this->featuredImageAlt = $this->featuredImageBeingUpdated->getCustomProperty('alt');

        $this->editingFeaturedImage = true;
    }

    public function updateFeaturedImage()
    {
        $this->featuredImageBeingUpdated->setCustomProperty('alt', $this->featuredImageAlt);

        $this->featuredImageBeingUpdated->save();

        $this->editingFeaturedImage = false;

        $this->notify('Featured image updated!');
    }

    public function removeFeaturedImage()
    {
        $this->article->clearMediaCollection('cover');
    }

    public function save()
    {
        $this->validate();

        $this->article->save();

        if ($this->featuredImage) {
            $this->article->addMedia($this->featuredImage->getRealPath())
                ->usingName(pathinfo($this->featuredImage->getClientOriginalName(), PATHINFO_FILENAME))
                ->usingFileName($this->featuredImage->getClientOriginalName())
                ->toMediaCollection('cover');
        }

        $this->featuredImage = null;

        $this->emit('refresh')->self();

        $this->notify('Article saved!');
    }

    public function removeBlogPost()
    {
        $this->article->delete();

        $this->redirect(route('employee.articles.list'));
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
        $this->article
            ->addMedia($this->mediaFile->getRealPath())
            ->usingName(pathinfo($this->mediaFile->getClientOriginalName(), PATHINFO_FILENAME))
            ->usingFileName($this->mediaFile->getClientOriginalName())
            ->toMediaCollection('media');

        $this->article->load('media');

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

        $this->article->load('media');
    }

    public function getMediaProperty()
    {
        return $this->article->media;
    }

    public function render()
    {
        return view('livewire.employee.article.article-detail')->layout('layouts.admin');
    }
}
