@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">Surat Masuk</h3>
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
                    <a href="#">Surat Masuk</a>
                </li>
            </ul>
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
                        <div class="card-title text-dark fw-bold">Kelola Surat Masuk</div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover align-middle">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>No Surat</th>
                                        <th>Tanggal</th>
                                        <th>Cabang Pengaju (CS)</th>
                                        <th>Perihal</th>
                                        <th>Nominal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($antreanSurat as $workflow)
                                        <tr>
                                            <td class="fw-bold">{{ $workflow->surat->nomor_surat }}</td>
                                            <td>{{ \Carbon\Carbon::parse($workflow->surat->tanggal)->format('d M Y') }}</td>
                                            <td>{{ $workflow->surat->creator->name ?? 'User Tidak Diketahui' }}</td>
                                            <td>{{ Str::limit($workflow->surat->perihal, 30) }}</td>
                                            <td class="text-success fw-bold">Rp.
                                                {{ number_format($workflow->surat->nominal, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('persetujuan-.show', $workflow->surat->id) }}"
                                                    class="btn btn-sm btn-primary shadow-sm">
                                                    <i class="fas fa-search me-1"></i> Cek Surat
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                <i class="fas fa-inbox fa-3x mb-3 text-light"></i><br>
                                                Tidak ada surat yang menunggu persetujuan Anda saat ini.
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
