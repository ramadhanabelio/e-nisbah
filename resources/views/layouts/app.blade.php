@extends('layouts.base')

@section('body')
    <div class="wrapper">
        @include('layouts.partials.sidebar')

        <div class="main-panel">
            @include('layouts.partials.navbar')

            <div class="container">
                @yield('content')
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>
@endsection
