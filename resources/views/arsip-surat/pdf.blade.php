<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Arsip Dokumen - {{ $surat->nomor_surat }}</title>
    <style>
        body {
            font-family: 'Arial', 'Helvetica', sans-serif !important;
            color: #000000 !important;
            margin: 0;
            padding: 20px;
            font-size: 11px;
            line-height: 1.3;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .text-justify {
            text-align: justify;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .header-title {
            border-bottom: 1.5px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .header-title h4 {
            margin: 0;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .header-title p {
            margin: 3px 0 0 0;
            font-size: 12px;
            font-weight: bold;
        }

        .table-form {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .table-form th,
        .table-form td {
            border: 1px solid #000000;
            padding: 4px;
            font-size: 10px;
            vertical-align: middle;
            text-align: center;
        }

        .table-form th {
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .table-signature {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .table-signature th,
        .table-signature td {
            border: 1px solid #000000;
            font-size: 10px;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
        }

        .table-signature th {
            background-color: #ffffff;
        }

        .signature-box {
            text-align: center;
            height: 70px;
            padding-top: 5px;
        }

        .signature-box img {
            max-height: 50px;
            display: block;
            margin: 0 auto;
        }

        .signer-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 10px;
            display: block;
            margin-top: 5px;
        }

        .date-row {
            text-align: left !important;
            padding-left: 8px !important;
        }
    </style>
</head>

<body>
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
        $pinsiDate = $approvalPinsi ? \Carbon\Carbon::parse($approvalPinsi->created_at)->format('d/m/Y') : $docDate;

        $pinbagName = $approvalPinbag ? $approvalPinbag->user->name : '';
        $pinbagDate = $approvalPinbag ? \Carbon\Carbon::parse($approvalPinbag->created_at)->format('d/m/Y') : $docDate;

        $pincabName = $approvalPincab ? $approvalPincab->user->name : '';
        $pincabDate = $approvalPincab ? \Carbon\Carbon::parse($approvalPincab->created_at)->format('d/m/Y') : $docDate;

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
        $dirOpsDate = $approvalDirOps ? \Carbon\Carbon::parse($approvalDirOps->created_at)->format('d/m/Y') : $docDate;

        $dirutName = $approvalDirut ? $approvalDirut->user->name : '';
        $dirutDate = $approvalDirut ? \Carbon\Carbon::parse($approvalDirut->created_at)->format('d/m/Y') : $docDate;
    @endphp

    <div class="header-title">
        <h4 class="fw-bold">FORMULIR PERMOHONAN SPESIAL NISBAH DEPOSITO MUDHARABAH</h4>
        <p>No. {{ $surat->nomor_surat }}</p>
    </div>

    <div class="text-justify mb-2">
        Sehubungan dengan rencana penempatan/perpanjangan dana Deposito Mudharabah berjangka Nasabah Bank Riau Kepri
        Syariah <span class="fw-bold">{{ strtoupper($surat->cabang ?? 'TANJUNG PINANG PAMEDAN') }}</span> dengan data sbb
        :
    </div>

    <table style="width: auto; border: none; margin-left: 30px; margin-bottom: 10px;">
        <tr>
            <td style="width: 110px;">Nama Nasabah</td>
            <td style="width: 10px;">:</td>
            <td class="fw-bold text-uppercase">{{ $surat->nama_nasabah }}</td>
        </tr>
        <tr>
            <td>Jenis Nasabah</td>
            <td>:</td>
            <td class="fw-bold text-uppercase">{{ $surat->jenis_nasabah }}</td>
        </tr>
    </table>

    <table class="table-form">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 13%;">Nomor<br>Rekening Deposito</th>
                <th style="width: 14%;">Nominal</th>
                <th style="width: 8%;">Jk. Waktu</th>
                <th style="width: 11%;">Tgl. Penempatan<br>Baru</th>
                <th style="width: 11%;">Tgl.<br>Perpanjangan</th>
                <th style="width: 11%;">Tanggal Jt<br>Tempo</th>
                <th style="width: 9%;">Spesial<br>Nisbah(*)</th>
                <th style="width: 9%;">Expected<br>Return (ER)(*)</th>
                <th style="width: 11%;">Jenis Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 10; $i++)
                @php $item = $surat->depositoItems->get($i) ?? null; @endphp
                <tr style="height: 20px;">
                    <td>{{ $i + 1 }}</td>
                    @if ($item)
                        <td class="fw-bold">{{ $item->nomor_rekening_deposito }}</td>
                        <td class="text-end fw-bold" style="padding-right: 5px;">
                            {{ number_format($item->nominal, 0, ',', '.') }}</td>
                        <td>{{ $item->jangka_waktu }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_penempatan_baru)->format('d/m/Y') }}</td>
                        <td>{{ $item->tanggal_perpanjangan ? \Carbon\Carbon::parse($item->tanggal_perpanjangan)->format('d/m/Y') : '' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                        <td class="fw-bold">{{ $item->spesial_nisbah }}%</td>
                        <td>{{ $item->expected_return }}%</td>
                        <td class="fw-bold" style="font-size: 9px;">{{ strtoupper($item->jenis_transaksi) }}</td>
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
            <tr style="background-color: #fff;">
                <td colspan="2" class="text-center fw-bold">TOTAL</td>
                <td class="text-end fw-bold" style="padding-right: 5px;">
                    {{ number_format($surat->total_nominal, 0, ',', '.') }}</td>
                <td colspan="7"></td>
            </tr>
        </tbody>
    </table>

    <table style="width: 100%; border: none; margin-top: 10px; margin-bottom: 15px;">
        <tr>
            <td style="width: 160px; vertical-align: top;">Total Relation / Outstanding</td>
            <td style="width: 10px; vertical-align: top;">:</td>
            <td class="fw-bold" style="vertical-align: top;">
                {{ number_format($surat->total_relation_outstanding, 0, ',', '.') }},- (
                {{ ucwords($surat->total_relation_outstanding_terbilang ?? '') }} )
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Alasan</td>
            <td style="vertical-align: top;">:</td>
            <td class="text-justify text-uppercase" style="vertical-align: top; font-size: 10px;">
                {{ $surat->alasan }}
            </td>
        </tr>
    </table>

    <div style="font-weight: bold; text-align: left;">Wewenang Cabang/Capem :</div>
    <div style="font-weight: bold; text-align: center; font-size: 10px; margin-bottom: 2px;">Yang Mengusulkan/Memutuskan
        :</div>
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
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPinsiPelnas-{{ str_replace(' ', '', $pinsiName) }}-{{ $surat->id }}"
                                alt="QR TTE">
                        @endif
                        <span class="signer-name">{{ $pinsiName }}</span>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        @if ($approvalPinbag)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPinbag-{{ str_replace(' ', '', $pinbagName) }}-{{ $surat->id }}"
                                alt="QR TTE">
                        @endif
                        <span class="signer-name">{{ $pinbagName }}</span>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        @if ($approvalPincab)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPincab-{{ str_replace(' ', '', $pincabName) }}-{{ $surat->id }}"
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

    <div style="font-weight: bold; text-align: left; margin-bottom: 2px;">Wewenang Kantor Pusat :</div>
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
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPemimpinBagian-{{ str_replace(' ', '', $pemimpinBagianName) }}-{{ $surat->id }}"
                                alt="QR TTE">
                        @endif
                        <span class="signer-name">{{ $pemimpinBagianName }}</span>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        @if ($approvalPemimpinDivisi)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByPemimpinDivisi-{{ str_replace(' ', '', $pemimpinDivisiName) }}-{{ $surat->id }}"
                                alt="QR TTE">
                        @endif
                        <span class="signer-name">{{ $pemimpinDivisiName }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="date-row">Tanggal : {{ $pemimpinBagianDate }}</td>
                <td class="date-row">Tanggal : {{ $pemimpinDivisiDate }}</td>
            </tr>
        </tbody>
    </table>

    <div style="font-weight: bold; text-align: left; margin-bottom: 2px;">Wewenang Direksi :</div>
    <table class="table-signature">
        <thead>
            <tr>
                <th style="width: 33.33%;">Direktur Dana & Jasa</th>
                <th style="width: 33.33%;">Direktur Pembiayaan/Operasional</th>
                <th style="width: 33.33%;">Direktur Utama</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="signature-box">
                        @if ($approvalDirDana)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByDirDana-{{ str_replace(' ', '', $dirDanaName) }}-{{ $surat->id }}"
                                alt="QR TTE">
                        @endif
                        <span class="signer-name">{{ $dirDanaName }}</span>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        @if ($approvalDirOps)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByDirOps-{{ str_replace(' ', '', $dirOpsName) }}-{{ $surat->id }}"
                                alt="QR TTE">
                        @endif
                        <span class="signer-name">{{ $dirOpsName }}</span>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        @if ($approvalDirut)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=VerifiedByDirut-{{ str_replace(' ', '', $dirutName) }}-{{ $surat->id }}"
                                alt="QR TTE">
                        @endif
                        <span class="signer-name">{{ $dirutName }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="date-row">Tanggal : {{ $dirDanaDate }}</td>
                <td class="date-row">Tanggal : {{ $dirOpsDate }}</td>
                <td class="date-row">Tanggal : {{ $dirutDate }}</td>
            </tr>
        </tbody>
    </table>

    <div style="font-size: 10px; margin-top: 10px;">
        <strong>Keterangan *):</strong><br>
        1. Untuk pengajuan spesial nisbah hanya mengisi spesial nisbah pada formulir permohonan spesial nisbah.<br>
        2. Untuk pengajuan spesial nisbah dengan tambahan bagi hasil mengisi spesial nisbah dan Expected Return (ER)
        pada formulir permohonan spesial nisbah.
    </div>
</body>

</html>
