@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="w-100 p-3" style="max-width: 560px;">
        <div class="text-center mb-5">
            <img src="{{ asset('img/brand.png') }}" alt="Logo Bank Riau Kepri Syariah" width="230" />
        </div>

        <div class="card card-round shadow-sm">
            <form action="{{ route('login.process') }}" method="POST">
                @csrf
                <div class="card-header text-center">
                    <h3 class="card-title mb-0">Selamat Datang</h3>
                    <h6 class="op-7">E-Nisbah</h6>
                </div>

                <div class="card-body">

                    @if (session('error'))
                        <div class="alert alert-warning">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->has('email'))
                        <div class="alert alert-danger">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                    @if ($errors->has('password'))
                        <div class="alert alert-danger">
                            {{ $errors->first('password') }}
                        </div>
                    @endif

                    <div class="form-group mb-1">
                        <label for="nik">NIK</label>
                        <input type="text" class="form-control" name="nik" id="nik" placeholder="Masukkan NIK"
                            required />
                    </div>

                    <div class="form-group mb-1">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Masukkan Password" required />
                    </div>
                </div>

                <div class="card-action pb-4">
                    <button class="btn btn-warning fw-bold w-100 mb-2">MASUK</button>
                </div>
            </form>
        </div>
    </div>
@endsection
