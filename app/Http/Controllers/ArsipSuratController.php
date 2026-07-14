<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ArsipSuratController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (!in_array(Auth::user()->role, ['cs', 'admin_pusat'])) {
                    abort(403, 'Akses ditolak. Anda bukan Customer Service atau Admin Pusat.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $surats = Surat::with('creator')
            ->where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('arsip-surat.index', compact('surats'));
    }

    public function download($id)
    {
        $surat = Surat::with(['depositoItems', 'creator'])->findOrFail($id);

        if ($surat->status !== 'selesai') {
            return back()->with('error', 'Hanya surat selesai yang dapat diunduh.');
        }

        $pdf = Pdf::loadView('arsip-surat.pdf', compact('surat'))
            ->setPaper('A4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $namaFile = 'E-Nisbah_' . str_replace('/', '_', $surat->nomor_surat) . '.pdf';

        return $pdf->download($namaFile);
    }
}
