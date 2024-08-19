<?php

namespace App\Http\Livewire\Guest;

use App\Models\Page;
use Livewire\Component;

class PageDetail extends Component
{
    public Page $page;

    public function render()
    {
        return view('livewire.guest.page-detail');
    }
}
