@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold">
                Ganti Password
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
                            Ganti Password
                        </div>
                    </div>

                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Password Lama</label>
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Password Baru</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="card-footer text-end pb-3 pt-3">
                            <button class="btn btn-success btn-round px-4">
                                Simpan Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
