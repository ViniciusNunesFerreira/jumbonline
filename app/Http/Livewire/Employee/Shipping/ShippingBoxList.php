<?php

namespace App\Http\Livewire\Employee\Shipping;

use App\Models\ShippingBox;
use App\Services\Shipping\PackageEstimator;
use App\Settings\ShippingSetting;
use Illuminate\Validation\Rule;
use Livewire\Component;

/**
 * Cadastro das embalagens reais (PP, P, M, G, GG…) usadas pelo
 * PackageEstimator na cotação de frete do site/PDV e na pré-postagem.
 *
 * Mesmo padrão de ExpenseCategoryList: um único modal atende criação e
 * edição via $editingId. Ao lado, os parâmetros da estimativa (densidade e
 * margem) e um simulador que mostra, em tempo real e com os parâmetros
 * ainda NÃO salvos, qual caixa seria escolhida para um peso qualquer —
 * para o administrador calibrar antes de afetar o checkout.
 */
class ShippingBoxList extends Component
{
    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public array $state = [
        'name' => '',
        'code' => '',
        'length_cm' => '',
        'width_cm' => '',
        'height_cm' => '',
        'max_weight_kg' => '',
        'is_active' => true,
    ];

    public array $settings = [
        'estimated_density' => PackageEstimator::DEFAULT_DENSITY,
        'packaging_margin_percent' => PackageEstimator::DEFAULT_MARGIN_PERCENT,
    ];

    public $simuladorPeso = 500;

    protected function rulesBox(): array
    {
        return [
            'state.name' => ['required', 'string', 'max:60'],
            'state.code' => ['required', 'string', 'max:10', 'alpha_dash', Rule::unique('shipping_boxes', 'code')->ignore($this->editingId)],
            'state.length_cm' => ['required', 'integer', 'min:' . PackageEstimator::MIN_COMPRIMENTO, 'max:' . PackageEstimator::MAX_LADO],
            'state.width_cm' => ['required', 'integer', 'min:' . PackageEstimator::MIN_LARGURA, 'max:' . PackageEstimator::MAX_LADO],
            'state.height_cm' => ['required', 'integer', 'min:' . PackageEstimator::MIN_ALTURA, 'max:' . PackageEstimator::MAX_LADO],
            'state.max_weight_kg' => ['nullable', 'numeric', 'min:0.1', 'max:30'],
            'state.is_active' => ['boolean'],
        ];
    }

    protected function rulesSettings(): array
    {
        return [
            'settings.estimated_density' => ['required', 'numeric', 'min:0.05', 'max:1.5'],
            'settings.packaging_margin_percent' => ['required', 'integer', 'min:0', 'max:50'],
        ];
    }

    protected $validationAttributes = [
        'state.name' => 'nome',
        'state.code' => 'código',
        'state.length_cm' => 'comprimento',
        'state.width_cm' => 'largura',
        'state.height_cm' => 'altura',
        'state.max_weight_kg' => 'peso máximo',
        'settings.estimated_density' => 'densidade',
        'settings.packaging_margin_percent' => 'margem de embalagem',
    ];

    public function mount(): void
    {
        try {
            $shippingSettings = app(ShippingSetting::class);

            $this->settings = [
                'estimated_density' => (float) $shippingSettings->estimated_density,
                'packaging_margin_percent' => (int) $shippingSettings->packaging_margin_percent,
            ];
        } catch (\Throwable $e) {
            // Settings migration ainda não executada: mantém os defaults do
            // estimador, e o primeiro "Salvar parâmetros" vai falhar com
            // mensagem clara (ver saveSettings()).
        }
    }

    public function create(): void
    {
        $this->reset('editingId', 'state');

        $this->resetErrorBag();

        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $box = ShippingBox::findOrFail($id);

        $this->editingId = $box->id;

        $this->state = [
            'name' => $box->name,
            'code' => $box->code,
            'length_cm' => $box->length_cm,
            'width_cm' => $box->width_cm,
            'height_cm' => $box->height_cm,
            'max_weight_kg' => $box->max_weight_g ? round($box->max_weight_g / 1000, 2) : '',
            'is_active' => $box->is_active,
        ];

        $this->resetErrorBag();

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->state['code'] = mb_strtoupper(trim((string) $this->state['code']));

        $this->validate($this->rulesBox());

        $soma = (int) $this->state['length_cm'] + (int) $this->state['width_cm'] + (int) $this->state['height_cm'];

        if ($soma > PackageEstimator::MAX_SOMA_LADOS) {
            $this->addError('state.length_cm', "A soma das dimensões ({$soma} cm) ultrapassa o limite de " . PackageEstimator::MAX_SOMA_LADOS . ' cm dos Correios.');

            return;
        }

        ShippingBox::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => trim($this->state['name']),
                'code' => $this->state['code'],
                'length_cm' => (int) $this->state['length_cm'],
                'width_cm' => (int) $this->state['width_cm'],
                'height_cm' => (int) $this->state['height_cm'],
                'max_weight_g' => $this->state['max_weight_kg'] !== '' && $this->state['max_weight_kg'] !== null
                    ? (int) round(((float) $this->state['max_weight_kg']) * 1000)
                    : null,
                'is_active' => (bool) $this->state['is_active'],
            ]
        );

        $this->showModal = false;

        $this->notify($this->editingId ? trans('Embalagem atualizada.') : trans('Embalagem cadastrada.'));
    }

    public function toggleActive(int $id): void
    {
        $box = ShippingBox::findOrFail($id);

        if ($box->is_active && ShippingBox::active()->count() === 1) {
            $this->notify(trans('Mantenha ao menos uma embalagem ativa — sem nenhuma, o frete passa a usar só a estimativa proporcional.'));

            return;
        }

        $box->update(['is_active' => ! $box->is_active]);

        $this->notify($box->is_active ? trans('Embalagem ativada.') : trans('Embalagem desativada — não será mais sugerida.'));
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;

        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $box = ShippingBox::find($this->deletingId);

        $this->showDeleteModal = false;
        $this->deletingId = null;

        if (! $box) {
            return;
        }

        // shipments.shipping_box_id é nullOnDelete: o histórico mantém as
        // medidas gravadas no próprio envio.
        $box->delete();

        $this->notify(trans('Embalagem excluída.'));
    }

    public function saveSettings(): void
    {
        $this->validate($this->rulesSettings());

        try {
            $shippingSettings = app(ShippingSetting::class);
            $shippingSettings->estimated_density = round((float) $this->settings['estimated_density'], 3);
            $shippingSettings->packaging_margin_percent = (int) $this->settings['packaging_margin_percent'];
            $shippingSettings->save();
        } catch (\Throwable $e) {
            $this->addError('settings.estimated_density', 'Não foi possível salvar — execute "php artisan migrate" para criar os parâmetros de frete.');

            return;
        }

        $this->notify(trans('Parâmetros de estimativa salvos. As próximas cotações já usam os novos valores.'));
    }

    public function getBoxesProperty()
    {
        return ShippingBox::query()
            ->withCount('shipments')
            ->get()
            ->sortBy(fn (ShippingBox $box) => $box->volume_cm3)
            ->values();
    }

    /**
     * Estimador montado com os parâmetros do formulário (mesmo não salvos)
     * e as caixas ativas — alimenta o simulador e a coluna de capacidade.
     */
    protected function previewEstimator(): PackageEstimator
    {
        $density = is_numeric($this->settings['estimated_density']) && (float) $this->settings['estimated_density'] > 0
            ? (float) $this->settings['estimated_density']
            : PackageEstimator::DEFAULT_DENSITY;

        $margin = is_numeric($this->settings['packaging_margin_percent'])
            ? max(0, (int) $this->settings['packaging_margin_percent'])
            : PackageEstimator::DEFAULT_MARGIN_PERCENT;

        return new PackageEstimator($density, $margin, $this->boxes->where('is_active', true));
    }

    public function getSimulacaoProperty(): ?array
    {
        $peso = is_numeric($this->simuladorPeso) ? (float) $this->simuladorPeso : 0;

        if ($peso <= 0) {
            return null;
        }

        $pacote = $this->previewEstimator()->estimarPorPeso($peso);

        return [
            'pacote' => $pacote,
            'ocupacao' => min(100, $pacote->ocupacaoPercentual()),
            'ocupacao_real' => $pacote->ocupacaoPercentual(),
        ];
    }

    public function render()
    {
        $estimator = $this->previewEstimator();

        return view('livewire.employee.shipping.shipping-box-list', [
            'boxes' => $this->boxes,
            'estimator' => $estimator,
            'simulacao' => $this->simulacao,
        ])->layout('layouts.admin');
    }
}