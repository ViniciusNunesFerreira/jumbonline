@props(['wrapperClasses' => '', 'value' => null])

<div
    x-data="{
        raw: {{ \Illuminate\Support\Js::from($value !== null && $value !== '' ? str_pad(preg_replace('/\D/', '', number_format((float) $value, 2, '', '')), 3, '0', STR_PAD_LEFT) : '') }},
        amountSync: @entangle($attributes->wire('model')).defer,
        get display() {
            if (this.raw === '') return '';
            let digits = this.raw.padStart(3, '0');
            let cents = digits.slice(-2);
            let reais = digits.slice(0, -2).replace(/^0+(?=\d)/, '');
            if (reais === '') reais = '0';
            reais = reais.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return reais + ',' + cents;
        },
        get amount() {
            let digits = this.raw.padStart(3, '0');
            let cents = digits.slice(-2);
            let reais = digits.slice(0, -2) || '0';
            return reais + '.' + cents;
        },
        sync() {
            this.amountSync = this.raw === '' ? '' : this.amount;
            this.$dispatch('change');
        },
        typeDigit(d) {
            if (this.raw.replace(/^0+/, '').length >= 9) return;
            this.raw += d;
            this.sync();
        },
        backspace() {
            this.raw = this.raw.slice(0, -1);
            this.sync();
        },
    }"
    x-init="sync()"
    {{ $attributes->whereStartsWith('x-on:')->merge([]) }}
    class="relative text-slate-500 {{ $wrapperClasses }} focus-within:text-slate-600 dark:focus-within:text-slate-200"
>
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <span class="sm:text-sm">
            {{ config('money.'.config('app.currency').'.symbol') }}
        </span>
    </div>

    <input
        type="text"
        inputmode="numeric"
        autocomplete="off"
        x-bind:value="display"
        x-on:keydown="
            if ($event.metaKey || $event.ctrlKey) return;
            if (['Tab','Shift','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Enter','Escape'].includes($event.key)) return;
            $event.preventDefault();
            if ($event.key === 'Backspace' || $event.key === 'Delete') { backspace(); return; }
            if (/^[0-9]$/.test($event.key)) { typeDigit($event.key); return; }
        "
        x-on:paste.prevent="
            const text = ($event.clipboardData || window.clipboardData).getData('text');
            raw = text.replace(/\D/g, '');
            sync();
        "
        {{ $attributes->except(['wire:model', 'wire:model.defer', 'wire:model.lazy', 'wire:model.debounce.300ms'])->whereDoesntStartWith('x-on:')->merge(['class' => 'pl-7']) }}
    />
</div>