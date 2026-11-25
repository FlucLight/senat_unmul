<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Services\SuratRenderer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JenisSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $jenis_surat = JenisSurat::latest()->get();

        return view('jenis_surat.index', compact('jenis_surat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $template = config('senatitor.default_template');

        return view('jenis_surat.create', compact('template'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->storeLogo($request);
        }

        JenisSurat::create($validated);

        return redirect()->route('jenis_surat.index');
    }

    /**
     * Display a preview of the template.
     */
    public function show(JenisSurat $jenis_surat, SuratRenderer $renderer): View
    {
        $konten = $renderer->render($jenis_surat->template_surat, [
            'nama_surat' => $jenis_surat->nama_surat,
            'nomor_surat' => '(nomor surat)',
            'tanggal_surat' => $renderer->tanggalIndonesia(now()),
        ], $jenis_surat->logo);

        return view('jenis_surat.show', compact('jenis_surat', 'konten'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisSurat $jenis_surat): View
    {
        return view('jenis_surat.edit', compact('jenis_surat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisSurat $jenis_surat): RedirectResponse
    {
        $validated = $this->validatedData($request);

        if ($request->boolean('hapus_logo') && $jenis_surat->logo) {
            $this->deleteLogo($jenis_surat->logo);
            $validated['logo'] = null;
        }

        if ($request->hasFile('logo')) {
            $this->deleteLogo($jenis_surat->logo);
            $validated['logo'] = $this->storeLogo($request);
        }

        $jenis_surat->update($validated);

        return redirect()->route('jenis_surat.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisSurat $jenis_surat): RedirectResponse
    {
        $this->deleteLogo($jenis_surat->logo);
        $jenis_surat->delete();

        return redirect()->route('jenis_surat.index');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'nama_surat' => ['required', 'string', 'max:255'],
            'template_surat' => ['required', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'ukuran_kertas' => ['required', Rule::in(array_keys(config('senatitor.paper_sizes')))],
            'margin_atas' => ['required', 'numeric', 'min:0', 'max:10'],
            'margin_bawah' => ['required', 'numeric', 'min:0', 'max:10'],
            'margin_kiri' => ['required', 'numeric', 'min:0', 'max:10'],
            'margin_kanan' => ['required', 'numeric', 'min:0', 'max:10'],
            'ulang_kop' => ['nullable', 'boolean'],
            'ulang_footer' => ['nullable', 'boolean'],
            'footer_surat' => ['nullable', 'string'],
        ], [], [
            'margin_atas' => 'margin atas',
            'margin_bawah' => 'margin bawah',
            'margin_kiri' => 'margin kiri',
            'margin_kanan' => 'margin kanan',
            'ukuran_kertas' => 'ukuran kertas',
            'footer_surat' => 'footer surat',
        ]);

        $validated['ulang_kop'] = $request->boolean('ulang_kop');
        $validated['ulang_footer'] = $request->boolean('ulang_footer');

        return $validated;
    }

    protected function storeLogo(Request $request): string
    {
        $file = $request->file('logo');
        $name = Str::uuid().'.'.$file->getClientOriginalExtension();
        $file->move(public_path('images/logos'), $name);

        return 'images/logos/'.$name;
    }

    protected function deleteLogo(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            File::delete($fullPath);
        }
    }
}
