<?php

namespace App\Http\Controllers;

use App\Models\Document;

class DashboardController extends Controller
{
    public function index()
    {
        $jenisCounts = Document::query()
            ->selectRaw('jenis, count(*) as total')
            ->groupBy('jenis')
            ->pluck('total', 'jenis')
            ->all();

        $perJenis = [];
        foreach (Document::JENIS as $key => $label) {
            $perJenis[$key] = [
                'label' => $label,
                'total' => $jenisCounts[$key] ?? 0,
            ];
        }

        $stats = [
            'per_jenis' => $perJenis,
            'draft' => Document::where('status', Document::STATUS_DRAFT)->count(),
            'final' => Document::where('status', Document::STATUS_FINAL)->count(),
            'bulan_ini' => Document::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->count(),
        ];

        $recent = Document::with('creator')->latest()->take(8)->get();

        return view('dashboard.index', compact('stats', 'recent'));
    }
}