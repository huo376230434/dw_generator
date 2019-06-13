@extends('tenancy.auth.authlayout')

@section('auth_content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                        <div class="alert alert-success" role="alert">

                            验证成功
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>

    setTimeout(function () {
        window.location.href="{{url('tenancy')}}"
    },2000)
</script>
