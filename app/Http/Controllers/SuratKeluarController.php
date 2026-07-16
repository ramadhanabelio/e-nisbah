<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Surat;
use Illuminate\Http\Request;
use App\Models\WorkflowSurat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

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

    public function index()
    {
        $surat_keluar = Surat::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('surat-keluar.index', compact('surat_keluar'));
    }

    public function create()
    {
        $nomorUrut = str_pad(
            Surat::count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        return view('surat-keluar.create', compact('nomorUrut'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'                     => 'required|date',
            'cabang'                      => 'required|string|max:255',
            'nama_nasabah'                => 'required|string|max:255',
            'jenis_nasabah'               => 'required|string|max:255',
            'total_nominal'               => 'required|numeric|min:0',
            'total_relation_outstanding'  => 'required|numeric|min:0',
            'alasan'                      => 'required|string',
            'lampiran'                    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

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
            $tahun = now()->year;

            $nomorUrut = str_pad(
                Surat::lockForUpdate()->count() + 1,
                3,
                '0',
                STR_PAD_LEFT
            );

            $kodeCabang = strtoupper(trim($request->cabang));

            $nomorSurat = "{$nomorUrut}/{$kodeCabang}/{$tahun}";

            DB::beginTransaction();

            $lampiran = null;

            if ($request->hasFile('lampiran')) {
                $lampiran = $request->file('lampiran')
                    ->store('lampiran-surat', 'public');
            }

            $surat_keluar = Surat::create([
                'nomor_surat'                => $nomorSurat,
                'tanggal'                    => $request->tanggal,
                'cabang'                     => $request->cabang,
                'nama_nasabah'               => $request->nama_nasabah,
                'jenis_nasabah'              => $request->jenis_nasabah,
                'total_nominal'              => $request->total_nominal,
                'total_relation_outstanding' => $request->total_relation_outstanding,
                'alasan'                     => $request->alasan,
                'lampiran'                   => $lampiran,
                'created_by'                 => Auth::id(),
                'status'                     => 'proses',
            ]);

            foreach ($request->items as $item) {
                $surat_keluar->depositoItems()->create([
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
                'surat_id'        => $surat_keluar->id,
                'current_role'    => 'pinsi_pelnas',
                'current_user_id' => null
            ]);

            DB::commit();
            return redirect()->route('surat-keluar.index')->with('success', 'Surat berhasil diajukan ke PINSI PELNAS.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
    }

    public function show(Surat $surat_keluar)
    {
        $workflow = WorkflowSurat::where('surat_id', $surat_keluar->id)->first();

        return view('surat-keluar.show', compact('surat_keluar', 'workflow'));
    }

    public function edit(Surat $surat_keluar)
    {
        if (!in_array($surat_keluar->status, ['draft', 'revisi'])) {
            return redirect()->route('surat-keluar.index')->with('error', 'Surat yang sudah diproses tidak dapat diubah.');
        }

        return view('surat-keluar.edit', compact('surat_keluar'));
    }

    public function update(Request $request, Surat $surat_keluar)
    {
        if (!in_array($surat_keluar->status, ['draft', 'revisi'])) {
            return redirect()->route('surat-keluar.index')->with('error', 'Surat yang sedang diproses atau selesai tidak dapat diubah.');
        }

        $request->validate([
            'nomor_surat'                 => 'required|unique:surats,nomor_surat,' . $surat_keluar->id,
            'tanggal'                     => 'required|date',
            'cabang'                      => 'required|string|max:255',
            'nama_nasabah'                => 'required|string|max:255',
            'jenis_nasabah'               => 'required|string|max:255',
            'total_nominal'               => 'required|numeric|min:0',
            'total_relation_outstanding'  => 'required|numeric|min:0',
            'alasan'                      => 'required|string',
            'lampiran'                    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

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

            $lampiran = $surat_keluar->lampiran;

            if ($request->hasFile('lampiran')) {

                if ($lampiran && Storage::disk('public')->exists($lampiran)) {
                    Storage::disk('public')->delete($lampiran);
                }

                $lampiran = $request->file('lampiran')
                    ->store('lampiran-surat', 'public');
            }

            $dataToUpdate = [
                'nomor_surat'                => $request->nomor_surat,
                'tanggal'                    => $request->tanggal,
                'cabang'                     => $request->cabang,
                'nama_nasabah'               => $request->nama_nasabah,
                'jenis_nasabah'              => $request->jenis_nasabah,
                'total_nominal'              => $request->total_nominal,
                'total_relation_outstanding' => $request->total_relation_outstanding,
                'alasan'                     => $request->alasan,
                'lampiran'                   => $lampiran,
            ];

            if ($surat_keluar->status === 'revisi') {
                $dataToUpdate['status'] = 'proses';
            }

            $surat_keluar->update($dataToUpdate);

            $surat_keluar->depositoItems()->delete();

            foreach ($request->items as $item) {
                $surat_keluar->depositoItems()->create([
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

            if ($surat_keluar->status === 'proses' || $dataToUpdate['status'] === 'proses') {
                WorkflowSurat::updateOrCreate(
                    ['surat_id' => $surat_keluar->id],
                    ['current_role' => 'pinsi_pelnas', 'current_user_id' => null]
                );
            }

            DB::commit();
            return redirect()->route('surat-keluar.index')->with('success', 'Surat berhasil diperbarui dan diajukan ulang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Surat $surat_keluar)
    {
        if ($surat_keluar->status !== 'draft') {
            return back()->with('error', 'Hanya surat berstatus draft yang dapat dihapus.');
        }

        $surat_keluar->delete();

        return redirect()->route('surat-keluar.index')->with('success', 'Draft surat berhasil dihapus.');
    }
}
