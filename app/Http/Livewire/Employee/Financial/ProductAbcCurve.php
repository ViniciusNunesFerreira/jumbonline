<?php

namespace App\Http\Livewire\Employee\Financial;

use App\Models\PrisonUnit;
use App\Services\FinancialMetricsService;
use Carbon\Carbon;
use Livewire\Component;

class ProductAbcCurve extends Component
{
    public string $from = '';

    public string $to = '';

    public string $prisonUnitId = '';

    protected $queryString = ['from', 'to', 'prisonUnitId'];

    public function mount()
    {
        $this->from = now()->startOfMonth()->toDateString();
        $this->to = now()->toDateString();
    }

    public function getPrisonUnitsProperty()
    {
        return PrisonUnit::orderBy('name')->get(['id', 'name']);
    }

    public function getCurveProperty()
    {
        $from = Carbon::parse($this->from)->startOfDay();
        $to = Carbon::parse($this->to)->endOfDay();

        return app(FinancialMetricsService::class)->abcCurve($from, $to, $this->prisonUnitId !== '' ? (int) $this->prisonUnitId : null);
    }

    public function render()
    {
        return view('livewire.employee.financial.product-abc-curve', [
            'curve' => $this->curve,
            'prisonUnits' => $this->prisonUnits,
        ])->layout('layouts.admin');
    }
}