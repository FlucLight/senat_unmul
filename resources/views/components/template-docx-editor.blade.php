@props(['fields' => config('senatitor.fields', [])])

<div class="mt-3 mb-4 overflow-hidden rounded-md border border-line bg-surface">
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-line px-4 py-3">
        <span class="flex items-center gap-2 text-sm">
            <strong class="font-semibold text-ink">Pratinjau Template</strong>
            <span class="rounded-sm border border-line-strong bg-paper-200 px-2 py-0.5 text-xs font-medium text-ink-muted" id="docx-filename-badge">template-surat.docx</span>
        </span>
        <span class="text-xs text-ink-muted">Tampilan dokumen (.docx) — isi dapat diedit langsung</span>
    </div>
    <div class="bg-paper-200 p-2">
        <div class="mb-2 flex flex-wrap items-center gap-2">
            <label class="text-sm text-ink">Jumlah lembar:</label>
            <input type="number" id="page-count" min="1" max="20" value="1"
                class="tnum h-8 w-24 rounded-md border border-control bg-surface px-2 text-sm text-ink focus-visible:outline-2 focus-visible:outline-offset-0 focus-visible:outline-focus">
            <x-button type="button" variant="secondary" size="sm" id="set-page-count">Atur jumlah lembar</x-button>
            <span class="text-xs text-ink-muted">Menambah atau menyisakan halaman (kop berulang diatur di opsi Halaman Lanjutan).</span>
        </div>
        <textarea id="template_surat" name="template_surat" {{ $attributes }}>{{ $slot }}</textarea>
    </div>
    <div class="border-t border-line bg-paper px-4 py-3 text-xs text-ink-muted">
        Gunakan kode isian: <code class="rounded-sm bg-paper-200 px-1 font-medium text-ink">[[NAMA_SURAT]]</code>, <code class="rounded-sm bg-paper-200 px-1 font-medium text-ink">[[NOMOR_SURAT]]</code>, <code class="rounded-sm bg-paper-200 px-1 font-medium text-ink">[[TANGGAL_SURAT]]</code>, <code class="rounded-sm bg-paper-200 px-1 font-medium text-ink">[[logo]]</code>. Gunakan Halaman Baru atau Atur jumlah lembar untuk menambah halaman.
    </div>
</div>

<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
    (function () {
        const fields = @json($fields);
        const namaInput = document.getElementById('nama_surat');
        const badge = document.getElementById('docx-filename-badge');

        function updateBadge() {
            if (!badge) return;
            let name = (namaInput && namaInput.value ? namaInput.value : 'template-surat').trim();
            name = name.replace(/[\\/:*?"<>|]+/g, '-');
            badge.textContent = name + '.docx';
        }
        if (namaInput) {
            namaInput.addEventListener('input', updateBadge);
            updateBadge();
        }

        tinymce.init({
            selector: 'textarea#template_surat',
            promotion: false,
            license_key: 'gpl',
            formats: {
                menjorok: { block: 'p', styles: { 'text-indent': '30px' } }
            },
            block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3; Header 4=h4; Menjorok=menjorok',
            table_sizing_mode: 'relative',
            height: 780,
            menubar: false,
            plugins: ['lists', 'table', 'pagebreak', 'searchreplace', 'wordcount', 'charmap', 'code'],
            toolbar1: 'undo redo | blocks fontfamily fontsizeinput | bold italic underline | alignleft aligncenter alignright alignjustify',
            toolbar2: 'bullist numlist outdent indent lineheight | table | kodeisian insertpagebreak | removeformat',
            skin: 'tinymce-5',
            relative_urls: false,
            remove_script_host: false,
            entity_encoding: 'raw',
            font_size_input_default_unit: 'pt',
            line_height_formats: '1 1.15 1.5 2',
            font_family_formats: 'Times New Roman=times new roman,times,serif; Arial=arial,helvetica,sans-serif; Calibri=calibri,sans-serif;',
            setup: function (ed) {
                ed.on('init', function () {
                    setTimeout(function () { ed.execCommand('fontName', false, 'Times New Roman'); ed.execCommand('fontSize', false, '12pt'); }, 300);
                });
                ed.on('BeforeExecCommand', function (e) {
                    if (e.command === 'mcePageBreak') {
                        e.preventDefault();
                        ed.insertContent('<div class="new-break" style="page-break-after: always;"><!-- pagebreak --></div><p></p>');
                    }
                });
                ed.ui.registry.addButton('insertpagebreak', {
                    text: 'Halaman Baru',
                    onAction: function () { ed.insertContent('<div class="new-break" style="page-break-after: always;"><!-- pagebreak --></div><p></p>'); }
                });
                ed.ui.registry.addMenuButton('kodeisian', {
                    text: 'Sisipkan Field',
                    fetch: function (callback) {
                        callback(fields.map(function (f) {
                            return { type: 'menuitem', text: f.label, onAction: function () { ed.insertContent('[[' + f.token + ']]'); } };
                        }));
                    }
                });

                // Helper: set total page count by inserting page breaks
                var setBtn = document.getElementById('set-page-count');
                var countInput = document.getElementById('page-count');
                if (setBtn && countInput) {
                    setBtn.addEventListener('click', function () {
                        var n = parseInt(countInput.value, 10);
                        if (isNaN(n) || n < 1) n = 1;
                        if (n > 20) n = 20;
                        var current = (ed.getContent().match(/<!-- pagebreak -->/g) || []).length + 1;
                        var toAdd = n - current;
                        for (var i = 0; i < toAdd; i++) {
                            ed.insertContent('<div class="new-break" style="page-break-after: always;"><!-- pagebreak --></div><p style="margin-top:12px;">(Lanjut halaman ' + (current + i + 1) + ')</p>');
                        }
                        if (toAdd < 0) {
                            alert('Dokumen saat ini memiliki ' + current + ' halaman. Hapus pemisah halaman (garis putus-putus) secara manual jika ingin mengurangi.');
                        }
                    });
                }
            },
            content_style: `
                html { background: whitesmoke; padding: 16px; }
                body {
                    font-family: "Times New Roman", Times, serif;
                    font-size: 12pt;
                    line-height: 1.4;
                    background: white;
                    width: 21cm;
                    min-height: 29.7cm;
                    margin: 0 auto;
                    padding: 3cm 3cm 3cm 4cm;
                    box-shadow: 0 0 8px lightgray;
                    box-sizing: border-box;
                }
                p { margin: 0 0 6pt 0; }
                table { border-collapse: collapse; }
                img { max-width: 100%; }
                .new-break { border-top: 1px dashed gray; margin: 24px 0; page-break-after: always; height: 0; }
            `
        });
    })();
</script>