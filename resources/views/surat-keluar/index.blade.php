@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">Surat Keluar</h3>
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
                    <a href="#">Surat Keluar</a>
                </li>
            </ul>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary btn-round shadow-sm">
                    <i class="fas fa-plus me-2"></i> Tambah Surat Keluar
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card card-round shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="card-title text-dark fw-bold">Kelola Surat Keluar</div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover align-middle">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th style="width: 5%">No.</th>
                                        <th>No Surat</th>
                                        <th>Nama Nasabah</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Total Nominal</th>
                                        <th>Status</th>
                                        <th class="text-center" style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($surats as $surat)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}.</td>
                                            <td>{{ $surat->nomor_surat }}</td>
                                            <td>{{ $surat->nama_nasabah }}</td>
                                            <td>{{ \Carbon\Carbon::parse($surat->tanggal)->format('d M Y') }}</td>
                                            <td>Rp. {{ number_format($surat->total_nominal, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($surat->status == 'proses')
                                                    <span class="badge bg-primary px-2 py-1.5">Diproses</span>
                                                @elseif($surat->status == 'revisi')
                                                    <span class="badge bg-warning text-dark px-2 py-1.5">Revisi</span>
                                                @elseif($surat->status == 'selesai')
                                                    <span class="badge bg-success px-2 py-1.5">Selesai</span>
                                                @else
                                                    <span class="badge bg-secondary px-2 py-1.5">Draft</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('surat-keluar.show', $surat->id) }}"
                                                        class="btn btn-sm btn-info text-white btn-round px-3 me-1">Detail</a>
                                                    @if (in_array($surat->status, ['draft', 'revisi']))
                                                        <a href="{{ route('surat-keluar.edit', $surat->id) }}"
                                                            class="btn btn-sm btn-warning btn-round text-dark px-3">Edit</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                                <br>Belum ada rekaman pengajuan e-nisbah terbaru.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
