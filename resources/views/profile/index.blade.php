@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">
                Profil Saya
            </h3>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">
                            Informasi Akun
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                                    value="{{ old('nik', $user->nik) }}">
                                @error('nik')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer text-end pb-3 pt-3">
                            <button class="btn btn-success btn-round px-4">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
