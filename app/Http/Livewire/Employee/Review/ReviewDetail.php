<?php

namespace App\Http\Livewire\Employee\Review;

use App\Models\Review;
use Livewire\Component;

class ReviewDetail extends Component
{
    public Review $review;

    public $confirmingReviewDeletion = false;

    public function mount()
    {
        $this->review->load([
            'product.reviews',
            'customer',
        ]);
    }

    public function publishReview()
    {
        $this->review->published_at = now();

        $this->review->save();

        $this->notify(trans('Review has been published successfully.'));
    }

    public function unpublishReview()
    {
        $this->review->published_at = null;

        $this->review->save();

        $this->notify(trans('Review has been unpublished successfully.'));
    }

    public function confirmReviewDeletion()
    {
        $this->confirmingReviewDeletion = true;
    }

    public function delete()
    {
        $this->review->delete();

        $this->redirect(route('employee.reviews.list'));
    }

    public function render()
    {
        return view('livewire.employee.review.review-detail')->layout('layouts.admin');
    }
}
