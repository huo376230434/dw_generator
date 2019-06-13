<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Tenancy::title() }} @if($header) | {{ $header }}@endif</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {!! Tenancy::css() !!}

    <script src="{{ Tenancy::jQuery() }}"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="custom" class="hold-transition {{config('admin.skin')}} {{join(' ', config('admin.layout'))}}">
<div class="wrapper">
    @include('tenancy.partials.header')

    {{--@include('admin::partials.sidebar')--}}

    <div class="content-wrapper" style="margin-left: 0px;" id="pjax-container">
        <div id="app">
        @yield('content')
        </div>
        {!! Tenancy::script() !!}

    </div>

    @include('tenancy.partials.footer')

</div>

<script>
    function LA() {}
    LA.token = "{{ csrf_token() }}";
</script>

<!-- REQUIRED JS SCRIPTS -->
{!! Tenancy::js() !!}

</body>
</html>
