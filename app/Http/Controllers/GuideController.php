<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuideController extends Controller
{
    /**
     * Tampilkan portal dokumentasi dan panduan operasional sistem ERP NOC.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Metadata dokumen
        $docMeta = [
            'document_title' => 'Manual Operasional & Dokumentasi Sistem Informasi ERP Laboratorium NOC',
            'organization'   => 'SMK Negeri 4 Malang - Bidang Keahlian Teknologi Informasi',
            'version'        => '2.4.0-Enterprise',
            'last_updated'   => 'September 2026',
            'classification' => 'Dokumen Internal / Operasional',
        ];

        return view('guide.index', compact('docMeta', 'user'));
    }
}
