@if($shipment->package_length_cm)
    <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs tabular-nums text-slate-500 dark:text-slate-400">
        @if($shipment->shippingBox)
            <span class="inline-flex items-center rounded-md bg-slate-100 px-1.5 py-0.5 text-[10px] font-bold text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ $shipment->shippingBox->code }}</span>
        @endif
        <span>{{ $shipment->package_length_cm }}×{{ $shipment->package_width_cm }}×{{ $shipment->package_height_cm }} cm</span>
        <span class="text-slate-300 dark:text-slate-600">·</span>
        <span>{{ number_format(($shipment->package_weight_g ?? 0) / 1000, 3, ',', '.') }} kg</span>
        @if($shipment->cost !== null)
            <span class="text-slate-300 dark:text-slate-600">·</span>
            <span class="font-semibold text-slate-700 dark:text-slate-300">R$ {{ number_format((float) $shipment->cost, 2, ',', '.') }}</span>
        @endif
        @if($shipment->package_source === \App\Services\Shipping\PackageDimensions::ORIGEM_MANUAL)
            <x-badge type="primary" size="xs">{{ __('Medido no balcão') }}</x-badge>
        @endif
    </div>
@endif