<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Surat;
use App\Models\ApprovalSurat;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDrafts = Surat::where('status', 'draft')->count();
        $totalProcesses = Surat::where('status', 'proses')->count();
        $totalRevisions = Surat::where('status', 'revisi')->count();
        $totalCompleted = Surat::where('status', 'selesai')->count();

        $totalSemuaSurat = Surat::count();
        $suratBulanIni = Surat::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $totalMenungguApproval = Surat::where('status', 'proses')->count();

        $timelineApprovals = ApprovalSurat::with(['surat', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalDrafts',
            'totalProcesses',
            'totalRevisions',
            'totalCompleted',
            'totalSemuaSurat',
            'suratBulanIni',
            'totalMenungguApproval',
            'timelineApprovals'
        ));
    }
}
