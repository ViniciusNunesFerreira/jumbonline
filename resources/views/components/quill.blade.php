<div
    x-data="{ content: @entangle($attributes->wire('model')).defer,  quill: null}"
    x-ref="quillEditor"
    x-model="content"
    x-init="
        function setupEditor() {
            quill = new Quill($refs.quillEditor, {
                modules: {
                    table: false,
                    'better-table-plus': {},
                    keyboard: { bindings: window.QuillBetterTablePlus?.keyboardBindings },
                    toolbar: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['blockquote'],
                        ['link'],
                    ],
                },
                placeholder: '{{ __('Write something...') }}',
                theme: 'snow'
            });
            quill.root.innerHTML = this.content;
            quill.on('text-change', function () {
               $dispatch('input', quill.root.innerHTML);
            });
            $data.quill = quill;
        }
    "
>
    <div class="mb-2 flex justify-end">
        <button
            type="button"
            x-on:click="quill.getModule('better-table-plus').insertTable(3, 3)"
            class="text-xs font-semibold text-purple hover:text-accent"
        >
            + Inserir tabela
        </button>
    </div>
</div>
