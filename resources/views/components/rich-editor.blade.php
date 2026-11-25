@props([
    'name' => 'body_html',
    'id' => 'body_html',
    'value' => '',
    'label' => 'Isi Dokumen',
    'hint' => null,
    'required' => false,
])

<div class="space-y-1.5" data-rich-editor-root>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-ink">{{ $label }} @if($required)<span class="font-normal text-ink-muted">(wajib)</span>@endif</label>
    @endif
    @if($hint)
        <p class="text-xs text-ink-muted">{{ $hint }}</p>
    @endif
    <div class="overflow-hidden rounded-md border border-control bg-surface">
        <textarea id="{{ $id }}" name="{{ $name }}" class="rich-editor-textarea min-h-[420px] w-full px-3 py-2 text-base text-ink">{{ old($name, $value) }}</textarea>
    </div>
    @error($name)
        <p class="text-xs text-danger-700">{{ $message }}</p>
    @enderror
</div>

@once
    @vite('resources/js/rich-editor.js')
@endonce

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var el = document.getElementById(@json($id));
        if (!el || !window.initRichEditor) return;
        window.initRichEditor(el, {});
        var form = el.closest('form');
        if (!form) return;
        var dirty = false;
        var submitting = false;
        form.addEventListener('submit', function () { submitting = true; });
        var markDirty = function () { dirty = true; };
        el.addEventListener('input', markDirty);
        el.addEventListener('change', markDirty);
        var bindEditor = function () {
            if (!window.tinymce) return;
            var ed = window.tinymce.get(@json($id));
            if (ed) {
                ed.on('change keyup undo redo', function () { dirty = true; });
            }
        };
        setTimeout(bindEditor, 500);
        setTimeout(bindEditor, 1500);
        window.addEventListener('beforeunload', function (e) {
            if (!dirty || submitting) return;
            e.preventDefault();
            e.returnValue = '';
        });
    });
</script>
