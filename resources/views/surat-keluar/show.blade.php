@extends('layouts.app')

@section('title', 'Detail Pengajuan E-Nisbah')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">Detail Pengajuan E-Nisbah</h3>
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
                    <a href="{{ route('surat.index') }}">Daftar Pengajuan E-Nisbah</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail Dokumen</a>
                </li>
            </ul>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold text-dark"></i>Detail Dokumen
                            {{ $surat->nomor_surat }}</h5>
                        <span
                            class="badge bg-{{ $surat->status == 'selesai' ? 'success' : ($surat->status == 'revisi' ? 'warning text-dark' : ($surat->status == 'proses' ? 'primary' : 'secondary')) }} px-3 py-2 fw-bold">
                            {{ strtoupper($surat->status == 'proses' ? 'DIPROSES' : $surat->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <tr>
                                    <td width="220" class="text-muted font-weight-bold">Tanggal Surat</td>
                                    <td>: {{ \Carbon\Carbon::parse($surat->tanggal)->format('d M Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">Cabang</td>
                                    <td>: {{ $surat->cabang ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">Nama Nasabah</td>
                                    <td>: {{ $surat->nama_nasabah }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">Jenis Nasabah</td>
                                    <td>: {{ $surat->jenis_nasabah }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">Total Nominal</td>
                                    <td>: Rp. {{ number_format($surat->total_nominal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">Total Relation Outstanding</td>
                                    <td>: Rp.
                                        {{ number_format($surat->total_relation_outstanding, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold">Alasan Pengajuan</td>
                                    <td>: {{ $surat->alasan }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-dark"></i>Rincian Rekening Deposito</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="min-width: 1200px;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>No Rekening</th>
                                        <th>Nominal</th>
                                        <th>Jk. Waktu</th>
                                        <th>Tgl Penempatan</th>
                                        <th>Tgl Perpanjangan</th>
                                        <th>Tgl Jatuh Tempo</th>
                                        <th>Spesial Nisbah</th>
                                        <th>Expected Return</th>
                                        <th>Jenis Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($surat->depositoItems as $item)
                                        <tr>
                                            <td>{{ $item->nomor_rekening_deposito }}</td>
                                            <td>Rp. {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $item->jangka_waktu }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_penempatan_baru)->format('d/m/Y') }}
                                            </td>
                                            <td>{{ $item->tanggal_perpanjangan ? Carbon::parse($item->tanggal_perpanjangan)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                            </td>
                                            <td>{{ $item->spesial_nisbah }}%</td>
                                            <td>{{ $item->expected_return }}%</td>
                                            <td><span
                                                    class="badge bg-light text-dark border">{{ $item->jenis_transaksi }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            Posisi Dokumen
                        </h5>
                    </div>
                    <div class="card-body text-center py-5">
                        @if ($surat->status == 'proses')
                            <div class="spinner-grow text-primary mb-3" role="status"></div>
                            <h6>Dokumen sedang berada di:</h6>
                            <h4 class="text-primary fw-bold">
                                {{ str_replace('_', ' ', strtoupper($workflow->current_role ?? 'PINSI PELNAS')) }}
                            </h4>
                            <p class="text-muted small px-3">Menunggu verifikasi, persetujuan, atau Tanda Tangan Elektronik
                                pejabat berwenang.</p>
                        @elseif($surat->status == 'revisi')
                            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                            <h6 class="fw-bold text-warning">Dokumen Perlu Revisi</h6>
                            <p class="text-muted small px-3">Terdapat catatan perbaikan pada pengajuan ini. Silakan lakukan
                                perbaikan segera.</p>
                            <div class="px-3">
                                <a href="{{ route('surat.edit', $surat->id) }}"
                                    class="btn btn-warning btn-round w-100 fw-bold text-dark">Perbaiki Sekarang</a>
                            </div>
                        @elseif($surat->status == 'selesai')
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h6 class="text-success fw-bold">Proses Selesai</h6>
                            <p class="text-muted small px-3">Dokumen E-Nisbah disetujui penuh dan telah diarsipkan ke dalam
                                sistem pusat.</p>
                        @else
                            <i class="fas fa-file-signature text-secondary fa-3x mb-3"></i>
                            <h6 class="fw-bold text-secondary">Status: Draft</h6>
                            <p class="text-muted small px-3">Surat belum dikirimkan ke Pinsi Pelnas.</p>
                            <div class="px-3">
                                <a href="{{ route('surat.edit', $surat->id) }}"
                                    class="btn btn-outline-secondary btn-round w-100">Lanjutkan Draft</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
