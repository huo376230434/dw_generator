@extends('tenancy.auth.layout')
@section('content')


@include('tenancy.auth.widgets.auth_navbar')
    <main class="py-4">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6"><img class="max-parent"  src="/img/login_bg.jpg" alt=""></div>
                <div class="col-md-6">
                    @yield('auth_content')

                </div>
            </div>

        </div>


        <hr>

    </main>


@include("admin.base_extends.pieces.copyright")

    @yield("custom_js")
@endsection


