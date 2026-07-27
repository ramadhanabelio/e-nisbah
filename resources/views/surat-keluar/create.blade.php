@extends('layouts.app')

@section('title', 'Form Surat Keluar')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">Form Surat Keluar</h3>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('surat-keluar.index') }}">Daftar Surat Keluar</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Form Surat Keluar</a></li>
            </ul>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white pb-0">
                        <h4 class="card-title mb-1 fw-bold text-dark">Pengajuan E-Nisbah Baru</h4>
                        <p class="text-muted small">Silakan lengkapi seluruh komponen data utama beserta perincian rekening
                            deposito di bawah.</p>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            @include('surat-keluar.form')

                            <div class="d-flex justify-content-end mt-4 border-top pt-3">
                                <a href="{{ route('surat-keluar.index') }}" class="btn btn-danger btn-round me-2">Batal</a>
                                <button type="submit" class="btn btn-success btn-round px-4">Kirim Surat ke PINSI
                                    PELNAS</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
