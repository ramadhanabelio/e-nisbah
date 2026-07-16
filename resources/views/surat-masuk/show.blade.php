@extends('layouts.app')

@section('title', 'Verifikasi Dokumen')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">Verifikasi Dokumen #{{ $surat->nomor_surat }}</h3>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('surat-masuk.index') }}">Antrean Persetujuan</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Proses Verifikasi</a>
                </li>
            </ul>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-danger btn-round btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-dark">Informasi Pengajuan</h5>
                    </div>

                    @if (in_array(Auth::user()->role, ['pinbag', 'pinidiv', 'direksi', 'dirut']))
                        <div class="alert alert-info border-0 rounded-0 m-0 small">
                            <strong><i class="fas fa-calculator me-1"></i> Aturan Batas Nominal:</strong><br>
                            Total Pengajuan: <span class="fw-bold text-dark">Rp
                                {{ number_format($surat->total_nominal, 0, ',', '.') }}</span><br>

                            @if (Auth::user()->role === 'pinbag')
                                {{ $surat->total_nominal >= 10000000000 ? 'Alur: Nominal ≥ 10 Miliar, dokumen akan diteruskan ke PINIDIV.' : 'Alur: Nominal < 10 Miliar, dokumen langsung menuju Penyelesaian Akhir.' }}
                            @elseif(Auth::user()->role === 'pinidiv')
                                {{ $surat->total_nominal >= 50000000000 ? 'Alur: Nominal ≥ 50 Miliar, dokumen akan diteruskan ke DIREKSI.' : 'Alur: Nominal < 50 Miliar, dokumen langsung menuju Penyelesaian Akhir.' }}
                            @elseif(Auth::user()->role === 'direksi')
                                {{ $surat->total_nominal >= 250000000000 ? 'Alur: Nominal ≥ 250 Miliar, dokumen akan diteruskan ke DIRUT.' : 'Alur: Nominal < 250 Miliar, dokumen langsung menuju Penyelesaian Akhir.' }}
                            @elseif(Auth::user()->role === 'dirut')
                                Surat akan langsung dialirkan ke Penyelesaian Akhir setelah Anda memberikan persetujuan.
                            @endif
                        </div>
                    @endif

                    <div class="card-body py-3">
                        <table class="table table-borderless table-sm align-middle mb-0 small text-dark">
                            <tr>
                                <td width="130" class="text-muted">Dibuat Oleh</td>
                                <td class="fw-bold">: {{ $surat->creator->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td width="130" class="text-muted">Cabang</td>
                                <td class="fw-bold">: {{ $surat->cabang ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Kirim</td>
                                <td>: {{ \Carbon\Carbon::parse($surat->tanggal)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Nasabah</td>
                                <td class="fw-bold">: {{ $surat->nama_nasabah }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jenis Nasabah</td>
                                <td>: {{ $surat->jenis_nasabah }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Nominal</td>
                                <td class="fw-bold">: Rp
                                    {{ number_format($surat->total_nominal, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card shadow-sm border-0 p-3 bg-light-gradient">
                    <h6 class="fw-bold text-dark mb-2">Keputusan Verifikasi</h6>
                    <p class="text-muted small mb-3">Tinjau isi draf di samping kanan dengan saksama sebelum mengambil
                        tindakan penandatanganan elektronik.</p>

                    <div class="d-grid gap-2">
                        @if (Auth::user()->role === 'admin_pusat')
                            <form action="{{ route('surat-masuk.approveAdmin', $surat->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin meneruskan dokumen kelolaan ini?')">
                                    <i class="fas fa-arrow-right me-2"></i> Teruskan Dokumen
                                </button>
                            </form>
                        @else
                            <form action="{{ route('surat-masuk.approve', $surat->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm"
                                    onclick="return confirm('Apakah Anda yakin isi dokumen sudah BENAR dan siap memberikan Tanda Tangani Elektronik?')">
                                    <i class="fas fa-check-circle me-2"></i> Setujui & Tanda Tangani
                                </button>
                            </form>
                        @endif

                        @if (Auth::user()->role !== 'admin_pusat')
                            <button type="button" class="btn btn-danger w-100 fw-bold shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle me-2"></i> Tolak / Kembalikan ke CS
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Preview</h5>
                    </div>
                    <div class="card-body bg-dark p-3" style="overflow-x: auto;">

                        <style>
                            .preview-paper {
                                width: 100%;
                                min-width: 820px;
                                max-width: 900px;
                                background-color: #ffffff !important;
                                color: #000000 !important;
                                font-family: 'Arial', 'Helvetica', sans-serif !important;
                                padding: 40px 45px !important;
                                box-sizing: border-box;
                                line-height: 1.4;
                                font-size: 11px;
                            }

                            .preview-paper table {
                                color: #000000 !important;
                                font-family: 'Arial', 'Helvetica', sans-serif !important;
                            }

                            .preview-paper .table-form {
                                width: 100%;
                                border-collapse: collapse !important;
                                margin-top: 10px;
                                margin-bottom: 10px;
                            }

                            .preview-paper .table-form th,
                            .preview-paper .table-form td {
                                border: 1px solid #000000 !important;
                                padding: 3px 4px !important;
                                font-size: 10px;
                                vertical-align: middle;
                                line-height: 1.2;
                            }

                            .preview-paper .table-form th {
                                font-weight: bold;
                                text-align: center;
                                background-color: #ffffff !important;
                            }

                            .preview-paper .table-signature {
                                width: 100%;
                                border-collapse: collapse !important;
                                margin-top: 3px;
                                margin-bottom: 10px;
                            }

                            .preview-paper .table-signature th,
                            .preview-paper .table-signature td {
                                border: 1px solid #000000 !important;
                                font-size: 10px;
                                text-align: center;
                                vertical-align: middle;
                            }

                            .preview-paper .table-signature th {
                                padding: 4px !important;
                                font-weight: bold;
                            }

                            .preview-paper .signature-box {
                                height: 85px;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                padding: 5px;
                                position: relative;
                            }

                            .preview-paper .signature-box img {
                                max-height: 55px;
                                width: auto;
                                margin-bottom: 2px;
                            }

                            .preview-paper .signer-name {
                                font-weight: bold;
                                text-decoration: underline;
                                text-transform: uppercase;
                                font-size: 10px;
                                margin-top: 1px;
                                display: inline-block;
                            }

                            .preview-paper .date-row {
                                text-align: left;
                                padding: 3px 8px !important;
                                font-size: 10px;
                            }

                            .preview-paper .text-underline {
                                text-decoration: underline;
                            }

                            .preview-paper .text-justify {
                                text-align: justify;
                            }
                        </style>

                        @php
                            $approvalPinsi = \App\Models\ApprovalSurat::with('user')
                                ->where('surat_id', $surat->id)
                                ->where('role', 'pinsi_pelnas')
                                ->where('status', 'approved')
                                ->first();

                            $approvalPinbag = \App\Models\ApprovalSurat::with('user')
                                ->where('surat_id', $surat->id)
                                ->where('role', 'pinbag_operasional')
                                ->where('status', 'approved')
                                ->first();

                            $approvalPincab = \App\Models\ApprovalSurat::with('user')
                                ->where('surat_id', $surat->id)
                                ->where('role', 'pincab')
                                ->where('status', 'approved')
                                ->first();

                            $approvalPemimpinBagian = \App\Models\ApprovalSurat::with('user')
                                ->where('surat_id', $surat->id)
                                ->where('role', 'pinbag')
                                ->where('status', 'approved')
                                ->first();

                            $approvalPemimpinDivisi = \App\Models\ApprovalSurat::with('user')
                                ->where('surat_id', $surat->id)
                                ->where('role', 'pinidiv')
                                ->where('status', 'approved')
                                ->first();

                            $approvalDirDana = \App\Models\ApprovalSurat::with('user')
                                ->where('surat_id', $surat->id)
                                ->where('role', 'direksi')
                                ->where('status', 'approved')
                                ->first();

                            $approvalDirOps = \App\Models\ApprovalSurat::with('user')
                                ->where('surat_id', $surat->id)
                                ->where('role', 'direksi_ops')
                                ->where('status', 'approved')
                                ->first();

                            $approvalDirut = \App\Models\ApprovalSurat::with('user')
                                ->where('surat_id', $surat->id)
                                ->where('role', 'dirut')
                                ->where('status', 'approved')
                                ->first();

                            $docDate = \Carbon\Carbon::parse($surat->tanggal ?? '2026-05-05')->format('d/m/Y');

                            $pinsiName = $approvalPinsi ? $approvalPinsi->user->name : '';
                            $pinsiDate = $approvalPinsi
                                ? \Carbon\Carbon::parse($approvalPinsi->created_at)->format('d/m/Y')
                                : $docDate;

                            $pinbagName = $approvalPinbag ? $approvalPinbag->user->name : '';
                            $pinbagDate = $approvalPinbag
                                ? \Carbon\Carbon::parse($approvalPinbag->created_at)->format('d/m/Y')
                                : $docDate;

                            $pincabName = $approvalPincab ? $approvalPincab->user->name : '';
                            $pincabDate = $approvalPincab
                                ? \Carbon\Carbon::parse($approvalPincab->created_at)->format('d/m/Y')
                                : $docDate;

                            $pemimpinBagianName = $approvalPemimpinBagian ? $approvalPemimpinBagian->user->name : '';
                            $pemimpinBagianDate = $approvalPemimpinBagian
                                ? \Carbon\Carbon::parse($approvalPemimpinBagian->created_at)->format('d/m/Y')
                                : $docDate;

                            $pemimpinDivisiName = $approvalPemimpinDivisi ? $approvalPemimpinDivisi->user->name : '';
                            $pemimpinDivisiDate = $approvalPemimpinDivisi
                                ? \Carbon\Carbon::parse($approvalPemimpinDivisi->created_at)->format('d/m/Y')
                                : $docDate;

                            $dirDanaName = $approvalDirDana ? $approvalDirDana->user->name : '';
                            $dirDanaDate = $approvalDirDana
                                ? \Carbon\Carbon::parse($approvalDirDana->created_at)->format('d/m/Y')
                                : $docDate;

                            $dirOpsName = $approvalDirOps ? $approvalDirOps->user->name : '';
                            $dirOpsDate = $approvalDirOps
                                ? \Carbon\Carbon::parse($approvalDirOps->created_at)->format('d/m/Y')
                                : $docDate;

                            $dirutName = $approvalDirut ? $approvalDirut->user->name : '';
                            $dirutDate = $approvalDirut
                                ? \Carbon\Carbon::parse($approvalDirut->created_at)->format('d/m/Y')
                                : $docDate;
                        @endphp

                        <div class="preview-paper mx-auto shadow position-relative">

                            <div class="text-center mb-3 pb-1" style="border-bottom: 1.5px solid #000;">
                                <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px; font-size: 13px;">FORMULIR PERMOHONAN
                                    SPESIAL NISBAH DEPOSITO MUDHARABAH</h5>
                                <div class="fw-bold" style="font-size: 11px;">No. {{ $surat->nomor_surat }}</div>
                            </div>

                            <div class="mb-2 text-justify" style="font-size: 11px;">
                                Sehubungan dengan rencana penempatan/perpanjangan dana Deposito Mudharabah berjangka Nasabah
                                Bank Riau Kepri Syariah <span class="fw-bold">{{ $surat->cabang }}</span> dengan data sbb
                                :
                            </div>

                            <div class="mb-2" style="margin-left: 35px;">
                                <table style="border: none; width: auto; font-size: 11px;" class="text-dark">
                                    <tr>
                                        <td style="width: 110px; padding: 1px 0;">Nama Nasabah</td>
                                        <td style="width: 15px; padding: 1px 0;">:</td>
                                        <td style="font-weight: bold; text-transform: uppercase; padding: 1px 0;">
                                            {{ $surat->nama_nasabah }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 1px 0;">Jenis Nasabah</td>
                                        <td style="padding: 1px 0;">:</td>
                                        <td style="font-weight: bold; text-transform: uppercase; padding: 1px 0;">
                                            {{ $surat->jenis_nasabah }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="table-responsive">
                                <table class="table-form">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%;">No</th>
                                            <th style="width: 12%;">Nomor<br>Rekening<br>Deposito</th>
                                            <th style="width: 15%;">Nominal</th>
                                            <th style="width: 8%;">Jk. Waktu</th>
                                            <th style="width: 11%;">Tgl. Penempatan<br>Baru</th>
                                            <th style="width: 11%;">Tgl.<br>Perpanjangan</th>
                                            <th style="width: 11%;">Tanggal Jt<br>Tempo</th>
                                            <th style="width: 8%;">Spesial<br>Nisbah(*)</th>
                                            <th style="width: 10%;">Expected<br>Return<br>(ER)(*)</th>
                                            <th style="width: 11%;">Jenis Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 0; $i < 10; $i++)
                                            @php
                                                $item = $surat->depositoItems->get($i) ?? null;
                                            @endphp
                                            <tr style="height: 18px;">
                                                <td class="text-center">{{ $i + 1 }}</td>
                                                @if ($item)
                                                    <td class="text-center fw-bold">{{ $item->nomor_rekening_deposito }}
                                                    </td>
                                                    <td class="text-end fw-bold" style="padding-right: 6px !important;">
                                                        {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                                    <td class="text-center">{{ $item->jangka_waktu }}</td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($item->tanggal_penempatan_baru)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $item->tanggal_perpanjangan ? \Carbon\Carbon::parse($item->tanggal_perpanjangan)->format('d/m/Y') : '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="text-center fw-bold">{{ $item->spesial_nisbah }}%</td>
                                                    <td class="text-center">{{ $item->expected_return }}%</td>
                                                    <td class="text-center fw-bold" style="font-size: 9px;">
                                                        {{ strtoupper($item->jenis_transaksi) }}</td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            </tr>
                                        @endfor

                                        <tr style="font-weight: bold; background-color: #ffffff !important; height: 18px;">
                                            <td colspan="2" class="text-center">TOTAL</td>
                                            <td class="text-end" style="padding-right: 6px !important;">
                                                {{ number_format($surat->total_nominal, 0, ',', '.') }}</td>
                                            <td colspan="7"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <table style="width: 100%; border: none; font-size: 11px; margin-top: 5px; line-height: 1.3;"
                                class="text-dark">
                                <tr>
                                    <td style="width: 165px; font-weight: normal; vertical-align: top; padding: 1px 0;">
                                        Total Relation / Outstanding</td>
                                    <td style="width: 15px; vertical-align: top; padding: 1px 0;">:</td>
                                    <td style="font-weight: bold; vertical-align: top; padding: 1px 0;">
                                        {{ number_format($surat->total_relation_outstanding, 0, ',', '.') }},- (
                                        {{ ucwords($surat->total_relation_outstanding_terbilang ?? 'Enam Puluh Tujuh Milyar') }}
                                        )
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: normal; vertical-align: top; padding: 1px 0;">Alasan</td>
                                    <td style="vertical-align: top; padding: 1px 0;">:</td>
                                    <td
                                        style="text-align: justify; vertical-align: top; padding: 1px 0; text-transform: uppercase; font-size: 10.5px;">
                                        {{ $surat->alasan }}
                                    </td>
                                </tr>
                            </table>

                            <div style="font-weight: bold; font-size: 11px; margin-top: 12px; text-align: left;">Wewenang
                                Cabang/Capem :</div>
                            <div style="font-weight: bold; font-size: 10px; text-align: center; margin-bottom: 2px;">Yang
                                Mengusulkan/Memutuskan :</div>
                            <table class="table-signature">
                                <thead>
                                    <tr>
                                        <th style="width: 33.33%;">Pinsi Pelnas/Operasional/Bisnis</th>
                                        <th style="width: 33.33%;">Pinbag Operasional/Bisnis</th>
                                        <th style="width: 33.33%;">Pemimpin KC/KCP/Kedai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="signature-box">
                                                @if ($approvalPinsi)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPinsiPelnas-{{ $pinsiName }}-Surat-{{ $surat->id }}"
                                                        alt="QR TTE">
                                                @endif
                                                <span class="signer-name">{{ $pinsiName }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="signature-box">
                                                @if ($approvalPinbag)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPinbag-{{ $pinbagName }}-Surat-{{ $surat->id }}"
                                                        alt="QR TTE">
                                                @endif
                                                <span class="signer-name">{{ $pinbagName }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="signature-box">
                                                @if ($approvalPincab)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPincab-{{ $pincabName }}-Surat-{{ $surat->id }}"
                                                        alt="QR TTE">
                                                @endif
                                                <span class="signer-name">{{ $pincabName }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="date-row">Tanggal : {{ $pinsiDate }}</td>
                                        <td class="date-row">Tanggal : {{ $pinbagDate }}</td>
                                        <td class="date-row">Tanggal : {{ $pincabDate }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div
                                style="font-weight: bold; font-size: 11px; margin-top: 10px; text-align: left; margin-bottom: 2px;">
                                Wewenang Kantor Pusat :</div>
                            <table class="table-signature">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Pemimpin Bagian</th>
                                        <th style="width: 50%;">Pemimpin Divisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="signature-box">
                                                @if ($approvalPemimpinBagian)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPemimpinBagian-{{ $pemimpinBagianName }}-Surat-{{ $surat->id }}"
                                                        alt="QR TTE">
                                                @endif
                                                <span class="signer-name">{{ $pemimpinBagianName }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="signature-box">
                                                @if ($approvalPemimpinDivisi)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPemimpinDivisi-{{ $pemimpinDivisiName }}-Surat-{{ $surat->id }}"
                                                        alt="QR TTE">
                                                @endif
                                                @if ($pemimpinDivisiName)
                                                    <span class="signer-name">{{ $pemimpinDivisiName }}</span>
                                                @else
                                                    <span style="color: #bbb; font-style: italic; font-size: 9px;"></span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="date-row">Tanggal : {{ $pemimpinBagianDate }}</td>
                                        <td class="date-row">Tanggal : {{ $pemimpinDivisiDate }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div
                                style="font-weight: bold; font-size: 11px; margin-top: 10px; text-align: left; margin-bottom: 2px;">
                                Wewenang Direksi :</div>
                            <table class="table-signature">
                                <thead>
                                    <tr>
                                        <th style="width: 33.33%;">Direktur Dana & Jasa</th>
                                        {{-- <th style="width: 33.33%;">Direktur Pembiayaan/Operasional</th> --}}
                                        <th style="width: 33.33%;">Direktur Utama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="signature-box">
                                                @if ($approvalDirDana)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByDirDana-{{ $dirDanaName }}-Surat-{{ $surat->id }}"
                                                        alt="QR TTE">
                                                @endif
                                                @if ($dirDanaName)
                                                    <span class="signer-name">{{ $dirDanaName }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <div class="signature-box">
                                                @if ($approvalDirOps)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByDirOps-{{ $dirOpsName }}-Surat-{{ $surat->id }}"
                                                        alt="QR TTE">
                                                @endif
                                                @if ($dirOpsName)
                                                    <span class="signer-name">{{ $dirOpsName }}</span>
                                                @endif
                                            </div>
                                        </td> --}}
                                        <td>
                                            <div class="signature-box">
                                                @if ($approvalDirut)
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByDirut-{{ $dirutName }}-Surat-{{ $surat->id }}"
                                                        alt="QR TTE">
                                                @endif
                                                @if ($dirutName)
                                                    <span class="signer-name">{{ $dirutName }}</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="date-row">Tanggal : {{ $dirDanaDate }}</td>
                                        {{-- <td class="date-row">Tanggal : {{ $dirOpsDate }}</td> --}}
                                        <td class="date-row">Tanggal : {{ $dirutDate }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div style="font-size: 10px; margin-top: 15px; line-height: 1.3;" class="text-dark">
                                <strong>Keterangan *):</strong><br>
                                1. Untuk pengajuan spesial nisbah hanya mengisi spesial nisbah pada formulir permohonan
                                spesial nisbah.<br>
                                2. Untuk pengajuan spesial nisbah dengan tambahan bagi hasil mengisi spesial nisbah dan
                                Expected Return (ER) pada formulir permohonan spesial nisbah.
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('surat-masuk.reject', $surat->id) }}" method="POST">
                @csrf
                <div class="modal-content text-dark">
                    <div class="modal-header bg-danger text-white py-3">
                        <h5 class="modal-title fw-bold" id="rejectModalLabel"><i class="fas fa-undo me-2"></i>Kembalikan
                            Surat ke Cabang</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="catatan" class="form-label fw-bold text-dark mb-1">Catatan Perbaikan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control border border-secondary" id="catatan" name="catatan" rows="4"
                                placeholder="Jelaskan secara rinci kesalahan penulisan atau kalkulasi data agar diperbaiki oleh CS..." required></textarea>
                            <small class="text-muted d-block mt-1">Catatan koreksi Anda akan tampil langsung di halaman
                                dashboard CS pembuat.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-round btn-sm"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-round btn-sm px-4">Kirim Catatan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
