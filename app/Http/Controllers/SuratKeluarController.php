<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\WorkflowSurat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratKeluarController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (Auth::user()->role !== 'cs') {
                    abort(403, 'Akses ditolak. Anda bukan Customer Service.');
                }
                return $next($request);
            }),
        ];
    }

    private function authorizeAccess(Surat $surat)
    {
        if ($surat->created_by !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses kesini.');
        }
    }

    public function index()
    {
        $surats = Surat::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('surat-keluar.index', compact('surats'));
    }

    public function create()
    {
        return view('surat-keluar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'                 => 'required|unique:surats,nomor_surat',
            'tanggal'                     => 'required|date',
            'cabang'                      => 'required|string|max:255',
            'nama_nasabah'                => 'required|string|max:255',
            'jenis_nasabah'               => 'required|string|max:255',
            'total_nominal'               => 'required|numeric|min:0',
            'total_relation_outstanding'  => 'required|numeric|min:0',
            'alasan'                      => 'required|string',
            'keterangan'                  => 'nullable|string',

            'items'                               => 'required|array|min:1',
            'items.*.nomor_rekening_deposito'     => 'required|string',
            'items.*.nominal'                     => 'required|numeric|min:0',
            'items.*.jangka_waktu'                => 'required|string',
            'items.*.tanggal_penempatan_baru'     => 'required|date',
            'items.*.tanggal_perpanjangan'        => 'nullable|date',
            'items.*.tanggal_jatuh_tempo'         => 'required|date',
            'items.*.spesial_nisbah'              => 'required|string',
            'items.*.expected_return'             => 'required|string',
            'items.*.jenis_transaksi'             => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $surat = Surat::create([
                'nomor_surat'                => $request->nomor_surat,
                'tanggal'                    => $request->tanggal,
                'cabang'                     => $request->cabang,
                'nama_nasabah'               => $request->nama_nasabah,
                'jenis_nasabah'              => $request->jenis_nasabah,
                'total_nominal'              => $request->total_nominal,
                'total_relation_outstanding' => $request->total_relation_outstanding,
                'alasan'                     => $request->alasan,
                'keterangan'                 => $request->keterangan,
                'created_by'                 => Auth::id(),
                'status'                     => 'proses',
            ]);

            foreach ($request->items as $item) {
                $surat->depositoItems()->create([
                    'nomor_rekening_deposito' => $item['nomor_rekening_deposito'],
                    'nominal'                 => $item['nominal'],
                    'jangka_waktu'            => $item['jangka_waktu'],
                    'tanggal_penempatan_baru' => $item['tanggal_penempatan_baru'],
                    'tanggal_perpanjangan'    => $item['tanggal_perpanjangan'] ?? null,
                    'tanggal_jatuh_tempo'     => $item['tanggal_jatuh_tempo'],
                    'spesial_nisbah'          => $item['spesial_nisbah'],
                    'expected_return'         => $item['expected_return'],
                    'jenis_transaksi'         => $item['jenis_transaksi'],
                ]);
            }

            WorkflowSurat::create([
                'surat_id'        => $surat->id,
                'current_role'    => 'pinsi_pelnas',
                'current_user_id' => null
            ]);

            DB::commit();
            return redirect()->route('surat-keluar.index')->with('success', 'Permohonan berhasil diajukan ke PINSI PELNAS.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
    }

    public function show(Surat $surat)
    {
        $this->authorizeAccess($surat);
        $workflow = WorkflowSurat::where('surat_id', $surat->id)->first();

        return view('surat-keluar.show', compact('surat', 'workflow'));
    }

    public function edit(Surat $surat)
    {
        $this->authorizeAccess($surat);

        if (!in_array($surat->status, ['draft', 'revisi'])) {
            return redirect()->route('surat-keluar.index')->with('error', 'Surat yang sudah diproses tidak dapat diubah.');
        }

        return view('surat-keluar.edit', compact('surat'));
    }

    public function update(Request $request, Surat $surat)
    {
        $this->authorizeAccess($surat);

        if (!in_array($surat->status, ['draft', 'revisi'])) {
            return redirect()->route('surat.index')->with('error', 'Surat tidak dapat diubah.');
        }

        $request->validate([
            'nomor_surat' => 'required|string|unique:surats,nomor_surat,' . $surat->id,
            'tanggal'     => 'required|date',
            'perihal'     => 'required|string|max:255',
            'nominal'     => 'required|numeric|min:0',
            'file_surat'  => 'nullable|file|mimes:pdf|max:5120',
            'keterangan'  => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $dataToUpdate = $request->only(['nomor_surat', 'tanggal', 'perihal', 'nominal', 'keterangan']);

            if ($request->hasFile('file_surat')) {
                if (Storage::exists('public/dokumen_surat/' . $surat->file_surat)) {
                    Storage::delete('public/dokumen_surat/' . $surat->file_surat);
                }

                $file = $request->file('file_surat');
                $filename = time() . '_' . Str::slug($request->nomor_surat) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/dokumen_surat', $filename);

                $dataToUpdate['file_surat'] = $filename;
            }

            if ($surat->status === 'revisi') {
                $dataToUpdate['status'] = 'proses';

                WorkflowSurat::updateOrCreate(
                    ['surat_id' => $surat->id],
                    ['current_role' => 'pinsi_pelnas', 'current_user_id' => null]
                );
            }

            $surat->update($dataToUpdate);

            DB::commit();

            return redirect()->route('surat-keluar.index')->with('success', 'Surat berhasil diperbarui dan diajukan ulang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function destroy(Surat $surat)
    {
        $this->authorizeAccess($surat);

        if ($surat->status !== 'draft') {
            return back()->with('error', 'Hanya surat berstatus draft yang dapat dihapus.');
        }

        if (Storage::exists('public/dokumen_surat/' . $surat->file_surat)) {
            Storage::delete('public/dokumen_surat/' . $surat->file_surat);
        }

        $surat->delete();

        return redirect()->route('surat-keluar.index')->with('success', 'Surat berhasil dihapus.');
    }
}
