<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('beranda');
        }

        $searchQuery = trim($request->query('nomor_surat', ''));
        $searchResult = null;
        $searched = false;

        if ($searchQuery !== '') {
            $searched = true;
            $searchResult = Document::where('status', Document::STATUS_FINAL)
                ->where('nomor_surat', $searchQuery)
                ->with('creator')
                ->first();
        }

        return view('welcome', [
            'searchQuery' => $searchQuery,
            'searchResult' => $searchResult,
            'searched' => $searched,
        ]);
    }
}
