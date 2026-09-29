<?php

namespace App\Http\Livewire\Employee\Order;

use App\Http\Livewire\Traits\WithBulkActions;
use App\Models\Order;
use App\Services\OrderSearchService;
use App\Services\StalledOrderService;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithBulkActions;
    use WithPagination;

    public $perPage = 10;

    public string $search = '';

    public bool $filterStalledOnly = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStalledOnly' => ['except' => false],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedFilterStalledOnly()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatedPage()
    {
        $this->clearSelection();
    }

    public function clearSearch()
    {
        $this->reset('search');
        $this->resetPage();
        $this->clearSelection();
    }

    protected function searchService(): OrderSearchService
    {
        return app(OrderSearchService::class);
    }

    protected function stalledOrderService(): StalledOrderService
    {
        return app(StalledOrderService::class);
    }

    /**
     * Interpretação do termo digitado (tipo detectado, rótulo e ícone),
     * exibida como indicador ao lado do campo de busca.
     */
    public function getSearchInterpretationProperty(): ?array
    {
        return $this->searchService()->interpret($this->search);
    }

    /**
     * Total de pedidos parados agora, independente do filtro/busca atual da
     * tela — alimenta o banner de alerta no topo da lista.
     */
    public function getStalledCountProperty(): int
    {
        return $this->stalledOrderService()->count();
    }

    public function getStalledThresholdDaysProperty(): int
    {
        return $this->stalledOrderService()->thresholdDays();
    }

    public function getRowsQueryProperty()
    {
        $query = Order::query()
            ->with([
                'orderDiscounts.orderItem',
                'orderItems:order_id,subtotal',
                'customer:id,name,email,phone,phone_country',
                'shipments:id,order_id,tracking_number',
            ])
            ->withSum('orderItems', 'quantity')
            ->withMin('paidPayments', 'created_at');

        $query = $this->searchService()->apply($query, $this->search);

        if ($this->filterStalledOnly) {
            $query = $this->stalledOrderService()->scopeStalled($query);
        }

        return $query->latest();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    /**
     * Metadados de exibição por linha (telefone formatado, rastreios,
     * motivos de correspondência da busca e status de "pedido parado"),
     * calculados uma única vez por render a partir das relações e
     * agregados já carregados — sem consultas adicionais.
     */
    protected function buildRowMeta($orders): array
    {
        $searchService = $this->searchService();
        $stalledService = $this->stalledOrderService();
        $meta = [];

        foreach ($orders as $order) {
            $trackings = $order->shipments
                ->pluck('tracking_number')
                ->filter()
                ->unique()
                ->values();

            $meta[$order->id] = [
                'phone' => $searchService->displayPhone($order->customer),
                'tracking' => $trackings->first(),
                'extra_trackings' => max($trackings->count() - 1, 0),
                'reasons' => $this->search !== '' ? $searchService->matchReasons($order, $this->search) : [],
                'stalled' => $stalledService->rowInfo($order),
            ];
        }

        return $meta;
    }

    public function render()
    {
        $orders = $this->rows;

        return view('livewire.employee.order.order-list', [
            'orders' => $orders,
            'rowMeta' => $this->buildRowMeta($orders),
            'searchInterpretation' => $this->searchInterpretation,
            'stalledCount' => $this->stalledCount,
            'stalledThresholdDays' => $this->stalledThresholdDays,
        ])->layout('layouts.admin');
    }
}