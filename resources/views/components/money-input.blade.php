@props(['value' => null, 'id' => null])

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
        sync() { this.amountSync = this.raw === '' ? '' : this.amount; },
        typeDigit(d) {
            if (this.raw.replace(/^0+/, '').length >= 9) return;
            this.raw += d;
            this.sync();
        },
        backspace() {
            this.raw = this.raw.slice(0, -1);
            this.sync();
        },
        clearAmount() {
            this.raw = '';
            this.sync();
        },
    }"
    x-init="sync()"
    {{ $attributes->except(['wire:model'])->merge(['class' => 'relative text-slate-500 focus-within:text-slate-600 dark:focus-within:text-slate-200']) }}
>
    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
        <span class="sm:text-sm">R$</span>
    </div>

    <input
        id="{{ $id }}"
        type="text"
        inputmode="numeric"
        autocomplete="off"
        placeholder="0,00"
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
        class="block w-full rounded-xl border-slate-300 pl-9 pr-9 text-right tabular-nums shadow-sm focus:border-accent-500 focus:ring-accent-500 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 sm:text-sm"
    />

    <button
        type="button"
        x-show="raw.length > 0"
        x-cloak
        x-on:click="clearAmount()"
        tabindex="-1"
        class="absolute inset-y-0 right-0 flex items-center pr-3"
    >
        <x-heroicon-s-x-circle class="h-5 w-5 text-slate-400 hover:text-slate-500 dark:hover:text-slate-400" />
    </button>
</div>