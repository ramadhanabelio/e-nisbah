<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\RevisiSurat;
use Illuminate\Http\Request;
use App\Models\ApprovalSurat;
use App\Models\WorkflowSurat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SuratMasukController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (Auth::user()->role === 'cs') {
                    abort(403, 'Akses ditolak. Customer Service tidak memiliki hak akses persetujuan.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $userRole = Auth::user()->role;

        $query = WorkflowSurat::with('surat.creator');

        if ($userRole === 'admin_pusat') {
            $query->whereIn('current_role', ['admin_pusat', 'admin_pusat_final']);
        } else {
            $query->where('current_role', $userRole);
        }

        $antreanSurat = $query->whereHas('surat', function ($q) {
            $q->where('status', 'proses');
        })
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('surat-masuk.index', compact('antreanSurat'));
    }

    public function show($id)
    {
        $surat = Surat::with(['workflow', 'depositoItems'])->findOrFail($id);

        $userRole = Auth::user()->role;

        if (!$surat->workflow) {
            return redirect()->route('surat-masuk.index')->with('error', 'Alur kerja surat tidak ditemukan.');
        }

        if ($surat->workflow->current_role !== $userRole || $surat->status !== 'proses') {
            return redirect()->route('surat-masuk.index')->with('error', 'Dokumen tidak tersedia atau sudah diproses.');
        }

        $approvedRoles = ApprovalSurat::where('surat_id', $surat->id)
            ->where('status', 'approved')
            ->pluck('role')
            ->toArray();

        $isFinalNominal = (
            ($surat->nominal < 10000000000 && in_array('pinbag', $approvedRoles)) ||
            ($surat->nominal < 50000000000 && in_array('pinidiv', $approvedRoles)) ||
            ($surat->nominal < 250000000000 && in_array('direksi', $approvedRoles)) ||
            in_array('dirut', $approvedRoles)
        );

        return view('surat-masuk.show', compact('surat', 'isFinalNominal'));
    }

    public function approve(Request $request, Surat $surat)
    {
        $user = Auth::user();
        $workflow = WorkflowSurat::where('surat_id', $surat->id)->first();

        if ($user->role === 'admin_pusat') {
            return back()->with('error', 'Gunakan tombol Teruskan.');
        }

        try {
            DB::beginTransaction();

            ApprovalSurat::create([
                'surat_id'    => $surat->id,
                'user_id'     => $user->id,
                'role'        => $user->role,
                'status'      => 'approved',
                'approved_at' => now(),
            ]);

            $nextRole = $this->getNextRole($user->role, $surat->nominal);

            $workflow->update([
                'current_role' => $nextRole,
                'current_user_id' => null
            ]);

            $nextRole = $this->getNextRole($user->role, $surat->nominal);

            if ($nextRole === 'selesai' || ($user->role === 'admin_pusat_final')) {
                $surat->update(['status' => 'selesai']);
                $workflow->update(['current_role' => 'selesai']);
            } else {
                $workflow->update([
                    'current_role' => $nextRole,
                    'current_user_id' => null
                ]);
            }

            DB::commit();

            return redirect()->route('surat-masuk.index')->with('success', 'Surat berhasil disetujui dan diteruskan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan' => 'required|string|min:5'
        ]);

        $user = Auth::user();
        $workflow = WorkflowSurat::where('surat_id', $surat->id)->first();

        if ($workflow->current_role !== $user->role) {
            return back()->with('error', 'Anda tidak berwenang menolak dokumen ini.');
        }

        try {
            DB::beginTransaction();

            ApprovalSurat::create([
                'surat_id'    => $surat->id,
                'user_id'     => $user->id,
                'role'        => $user->role,
                'status'      => 'rejected',
                'catatan'     => $request->catatan,
                'approved_at' => now(),
            ]);

            RevisiSurat::create([
                'surat_id' => $surat->id,
                'user_id'  => $user->id,
                'catatan'  => $request->catatan,
            ]);

            $workflow->update(['current_role' => 'cs', 'current_user_id' => $surat->created_by]);
            $surat->update(['status' => 'revisi']);

            DB::commit();

            return redirect()->route('surat-masuk.index')->with('success', 'Surat berhasil dikembalikan ke Cabang untuk perbaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    private function getNextRole($currentRole, $nominal)
    {
        switch ($currentRole) {
            case 'pinsi_pelnas':
                return 'pinbag_operasional';
            case 'pinbag_operasional':
                return 'pincab';
            case 'pincab':
                return 'admin_pusat';
            case 'admin_pusat':
                return 'pinbag';

            case 'pinbag':
                return ($nominal >= 10000000000) ? 'pinidiv' : 'admin_pusat';

            case 'pinidiv':
                return ($nominal >= 50000000000) ? 'direksi' : 'admin_pusat';

            case 'direksi':
                return ($nominal >= 250000000000) ? 'dirut' : 'admin_pusat';

            case 'dirut':
                return 'selesai';

            default:
                return 'admin_pusat';
        }
    }

    public function direct(Request $request, Surat $surat)
    {
        $user = Auth::user();
        $workflow = WorkflowSurat::where('surat_id', $surat->id)->first();

        try {
            DB::beginTransaction();

            if ($user->role !== 'admin_pusat') {
                ApprovalSurat::create([
                    'surat_id'    => $surat->id,
                    'user_id'     => $user->id,
                    'role'        => $user->role,
                    'status'      => 'approved',
                    'approved_at' => now(),
                ]);
            }

            $nextRole = $this->getNextRole($user->role, $surat->nominal);

            if ($request->has('action') && $request->action === 'selesai') {
                $surat->update(['status' => 'selesai']);
                $workflow->update([
                    'current_role' => 'selesai',
                    'current_user_id' => null
                ]);

                ApprovalSurat::create([
                    'surat_id'    => $surat->id,
                    'user_id'     => $user->id,
                    'role'        => $user->role,
                    'status'      => 'approved',
                    'approved_at' => now(),
                    'catatan'     => 'Dokumen diselesaikan dan diarsipkan oleh ' . $user->name,
                ]);
            } else {
                $workflow->update([
                    'current_role' => $nextRole,
                    'current_user_id' => null
                ]);
            }

            DB::commit();
            return redirect()->route('surat-masuk.index')->with('success', 'Surat berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}
