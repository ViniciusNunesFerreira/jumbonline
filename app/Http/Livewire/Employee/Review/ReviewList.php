<?php

namespace App\Http\Livewire\Employee\Review;

use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public $perPage = 10;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedPage()
    {
        $this->clearSelection();
    }

    public function getRowsQueryProperty()
    {
        return Review::query()
            ->with('product', 'customer')
            ->when($this->search, fn($query, $search) => $query->where('title', 'like', '%' . $search . '%')->orWhere('content', 'like', '%' . $search . '%'))
            ->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.employee.review.review-list', [
            'reviews' => $this->rows,
        ])->layout('layouts.admin');
    }
}
