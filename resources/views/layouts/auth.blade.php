@extends('layouts.base')

@section('body')
    <div class="container-fluid min-vh-100">
        <div class="row min-vh-100">
            <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center auth-bg">
                <div class="auth-logo">
                    <img src="{{ asset('img/white.png') }}" alt="Logo PHR">
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center justify-content-center">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
