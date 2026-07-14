<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Surat;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDrafts = Surat::where('status', 'draft')->count();
        $totalProcesses = Surat::where('status', 'process')->count();
        $totalRevisions = Surat::where('status', 'revision')->count();
        $totalCompleted = Surat::where('status', 'completed')->count();

        return view('dashboard', compact('totalDrafts', 'totalProcesses', 'totalRevisions', 'totalCompleted'));
    }
}
