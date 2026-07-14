@extends('layouts.app')

@section('title', 'Arsip Surat')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">Arsip Surat</h3>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Arsip Surat</a></li>
            </ul>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card card-round shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="card-title text-dark fw-bold">Kelola Arsip Surat</div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover align-middle">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th style="width: 5%">No.</th>
                                        <th>Nomor Surat</th>
                                        <th>Nama Nasabah</th>
                                        <th>Tanggal Disetujui</th>
                                        <th>Total Nominal</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center" style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($surats as $surat)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}.</td>
                                            <td>{{ $surat->nomor_surat }}</td>
                                            <td>{{ $surat->nama_nasabah }}</td>
                                            <td>{{ \Carbon\Carbon::parse($surat->updated_at)->format('d M Y') }}</td>
                                            <td>Rp. {{ number_format($surat->total_nominal, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-success px-2 py-1.5">Selesai</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('arsip.download', $surat->id) }}"
                                                    class="btn btn-sm btn-danger btn-round shadow-sm fw-bold">
                                                    <i class="fas fa-file-pdf me-1"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                                <br>Tidak ada data tersedia.
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
