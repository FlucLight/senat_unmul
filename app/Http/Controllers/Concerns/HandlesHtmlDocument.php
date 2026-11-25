<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Document;
use App\Services\DocumentTemplateService;
use App\Services\HtmlSanitizer;
use App\Services\NomorSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait HandlesHtmlDocument
{
    protected function htmlTemplates(string $jenis): array
    {
        $svc = app(DocumentTemplateService::class);
        $templates = $svc->forType($jenis);
        $bodies = [];
        foreach ($templates as $key => $tpl) {
            $bodies[$key] = $svc->render($jenis, $key);
        }

        return [$templates, $bodies];
    }

    protected function renderHtmlCreate(string $jenis, string $label, string $view)
    {
        [$templates, $bodies] = $this->htmlTemplates($jenis);
        $defaultKey = app(DocumentTemplateService::class)->defaultKey($jenis) ?? 'default';

        return view($view, [
            'document' => null,
            'templates' => $templates,
            'templateBodies' => $bodies,
            'selectedTemplate' => $defaultKey,
            'meta' => ['perihal' => '', 'tanggal_surat' => now()->toDateString()],
            'bodyHtml' => $bodies[$defaultKey] ?? '',
        ]);
    }

    protected function renderHtmlEdit(Document $document, string $jenis, string $view, string $legacyView)
    {
        $content = (array) $document->content;
        if (($content['format'] ?? null) !== 'html') {
            return view($legacyView, [
                'document' => $document,
                'content' => array_merge($this->defaultContent(), $content),
            ]);
        }

        [$templates, $bodies] = $this->htmlTemplates($jenis);
        $meta = (array) ($content['meta'] ?? []);
        $selected = $content['template_key'] ?? (app(DocumentTemplateService::class)->defaultKey($jenis) ?? 'default');

        return view($view, [
            'document' => $document,
            'templates' => $templates,
            'templateBodies' => $bodies,
            'selectedTemplate' => $selected,
            'meta' => [
                'perihal' => $meta['perihal'] ?? $document->judul,
                'tanggal_surat' => $meta['tanggal_surat'] ?? now()->toDateString(),
            ],
            'bodyHtml' => (string) ($content['body_html'] ?? ''),
        ]);
    }

    protected function saveHtmlDocument(Request $request, string $jenis, ?Document $document)
    {
        $isFinal = $request->input('action') === 'final';
        $isPreview = $request->input('action') === 'preview';

        $validated = $request->validate([
            'perihal' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'date'],
            'template_key' => ['nullable', 'string', 'max:100'],
            'body_html' => [$isFinal ? 'required' : 'nullable', 'string'],
        ]);

        $sanitizer = app(HtmlSanitizer::class);
        $bodyHtml = $sanitizer->sanitize($validated['body_html'] ?? '');

        if ($isFinal && trim(strip_tags($bodyHtml)) === '') {
            return back()->withInput()->withErrors(['body_html' => 'Isi dokumen wajib diisi sebelum finalisasi.']);
        }

        $content = [
            'format' => 'html',
            'schema_version' => 2,
            'template_key' => $validated['template_key'] ?: (app(DocumentTemplateService::class)->defaultKey($jenis) ?? 'default'),
            'body_html' => $bodyHtml,
            'meta' => [
                'perihal' => $validated['perihal'],
                'tanggal_surat' => $validated['tanggal_surat'],
            ],
        ];

        if ($document) {
            $content = array_merge((array) $document->content, $content);
        }

        return DB::transaction(function () use ($request, $jenis, $document, $validated, $content, $isFinal, $isPreview) {
            if (! $document) {
                $document = Document::create([
                    'jenis' => $jenis,
                    'judul' => $validated['perihal'],
                    'status' => Document::STATUS_DRAFT,
                    'created_by' => auth()->id(),
                    'content' => $content,
                ]);
            } else {
                $document->update([
                    'judul' => $validated['perihal'],
                    'content' => $content,
                ]);
            }

            if ($isFinal) {
                $nomorSurat = app(NomorSuratService::class)->generate($jenis);
                $document->update([
                    'status' => Document::STATUS_FINAL,
                    'nomor_surat' => $nomorSurat,
                    'finalized_at' => now(),
                    'qr_verification_url' => route('dokumen.verifikasi', $document->id),
                ]);

                $fresh = $document->fresh()->load('creator');
                $payload = app(\App\Services\Document\DocumentRenderer::class)->pdfPayload($fresh);
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($payload['view'], $payload['data'])->setPaper('a4');
                $path = 'dokumen/' . $document->id . '.pdf';
                Storage::disk('public')->put($path, $pdf->output());
                $document->update(['pdf_path' => $path]);

                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Dokumen berhasil difinalisasi dan nomor surat resmi telah diterbitkan.');
            }

            if ($isPreview) {
                return redirect()->route('dokumen.show', $document)
                    ->with('status', 'Draf berhasil disimpan. Ini pratinjau dokumen Anda.');
            }

            $routeName = 'dokumen.' . str_replace('_', '-', $jenis) . '.edit';

            return redirect()->route($routeName, $document)
                ->with('status', 'Draf dokumen berhasil disimpan.');
        });
    }
}
