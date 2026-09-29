<?php

namespace App\Http\Livewire\Employee\Order\Components;

use App\Models\Order;
use App\Services\OrderTimelineService;
use Livewire\Component;

class OrderTimeline extends Component
{
    public Order $order;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->order->load(['payments', 'shipments', 'refunds']);
    }

    public function getEventsProperty()
    {
        return app(OrderTimelineService::class)->build($this->order);
    }

    public function render()
    {
        return view('livewire.employee.order.components.order-timeline', [
            'events' => $this->events,
        ]);
    }
}