@extends('layouts.app')

@section('title', 'Form Revisi Surat Keluar')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">Form Revisi Surat Keluar</h3>
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="{{ route('surat-keluar.index') }}">Daftar Surat Keluar</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Form Revisi Surat Keluar</a></li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title mb-1 fw-bold text-dark">Perbaiki E-Nisbah</h4>
                                <p class="text-muted small">Lakukan koreksi data berdasarkan catatan penolakan/revisi yang
                                    diberikan.</p>
                            </div>
                        </div>
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

                        <form action="{{ route('surat-keluar.update', $surat_keluar->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @include('surat-keluar.form')

                            <div class="d-flex justify-content-end mt-4 border-top pt-3">
                                <a href="{{ route('surat-keluar.index') }}" class="btn btn-danger btn-round me-2">Batal</a>
                                <button type="submit" class="btn btn-warning text-dark btn-round px-4 fw-bold">
                                    Simpan dan Ajukan Ulang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
