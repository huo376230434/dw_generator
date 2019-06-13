
@extends("tenancy.auth.authlayout")
@section("auth_content")


    <h3 class="pt-3 pl-3">重置密码</h3>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('tenancy.password.email') }}">
            @csrf

            @component('bootstrop4.form.input',['name'=> 'email','label' => '邮箱:','type' => 'email'])
            @endcomponent



            <div class="form-group my-4 ">
                <div class="">
                    <button type="submit" class="btn btn-primary my-2 d-block btn-block">
                     发送邮件
                    </button>
                    <a class="btn btn-link" href="{{ route('tenancy.login')}}">
                        登录
                    </a>
                    <a class="btn btn-link" href="{{ route('tenancy.register')}}">
                        注册
                    </a>
                </div>
            </div>
        </form>
    </div>

@endsection


