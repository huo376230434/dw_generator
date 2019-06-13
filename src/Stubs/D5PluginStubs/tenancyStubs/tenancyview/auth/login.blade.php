
@extends("tenancy.auth.authlayout")
@section("auth_content")
    <h3 class="pt-3 pl-3">登录</h3>
    <hr>

    <div class="card-body">
        <form id="auth_form" action="{{route("tenancy.login")}}" method="POST" >
            @csrf

            @component('bootstrop4.form.input',['name' => 'account','label' => "用户名:"])
            @endcomponent

            @component('bootstrop4.form.input',['name'=> 'password','label' => '密码:','type' => 'password'])
            @endcomponent

            <div  class="form-group   mt-5">
                <div>
                    <button type="submit" class="btn btn-primary btn-block  login_btn">
                        登录
                    </button>
                    <a class="btn btn-link" href="{{ route('tenancy.register')}}">
                        注册
                    </a>
                    <a class="btn btn-link" href="{{ route('tenancy.password.request')}}">
                        忘记密码？
                    </a>
                </div>
            </div>
        </form>
    </div>

@endsection


