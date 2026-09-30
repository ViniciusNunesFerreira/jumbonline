

@props(['value' => null, 'id' => null])


<div
    wire:ignore
    x-data="{
        raw: {{ \Illuminate\Support\Js::from($value !== null ? number_format((float) $value, 2, ',', '') : '') }},
        amountSync: @entangle($attributes->wire('model')).defer,
        get display() {
            if (this.raw === '') return '';
            let [intPart, decPart] = this.raw.split(',');
            intPart = intPart.replace(/^0+(?=\d)/, '');
            if (intPart === '') intPart = '0';
            intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return decPart !== undefined ? (intPart + ',' + decPart) : intPart;
        },
        get amount() {
            let [intPart, decPart] = this.raw.split(',');
            intPart = (intPart === '' ? '0' : intPart);
            decPart = (decPart ?? '').padEnd(2, '0').slice(0, 2);
            return intPart + '.' + decPart;
        },
        sync() {
            this.amountSync = this.raw === '' ? '' : this.amount;
        },
        typeChar(c) {
            const parts = this.raw.split(',');
            if (c === ',') {
                if (parts.length > 1) return;
                this.raw += ',';
                this.sync();
                return;
            }
            if (parts.length > 1 && parts[1].length >= 2) return;
            if (parts.length === 1 && parts[0].replace(/^0+/, '').length >= 9) return;
            this.raw += c;
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
        inputmode="decimal"
        autocomplete="off"
        placeholder="0,00"
        x-bind:value="display"
        x-on:keydown="
            if ($event.metaKey || $event.ctrlKey) return;
            if (['Tab','Shift','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Enter','Escape'].includes($event.key)) return;
            $event.preventDefault();
            if ($event.key === 'Backspace' || $event.key === 'Delete') { backspace(); return; }
            if (/^[0-9]$/.test($event.key)) { typeChar($event.key); return; }
            if ($event.key === ',' || $event.key === '.') { typeChar(','); return; }
        "
        x-on:paste.prevent="
            const text = ($event.clipboardData || window.clipboardData).getData('text');
            const cleaned = text.replace(/[^0-9,.]/g, '');
            const lastSep = Math.max(cleaned.lastIndexOf(','), cleaned.lastIndexOf('.'));
            if (lastSep !== -1 && (cleaned.length - lastSep - 1) <= 2) {
                const intPart = cleaned.slice(0, lastSep).replace(/[,.]/g, '');
                const decPart = cleaned.slice(lastSep + 1);
                raw = intPart + ',' + decPart;
            } else {
                raw = cleaned.replace(/[,.]/g, '');
            }
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