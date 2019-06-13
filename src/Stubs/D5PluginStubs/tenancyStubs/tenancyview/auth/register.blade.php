
@extends("tenancy.auth.authlayout")
@section("auth_content")
    <h3 class="pt-3 pl-3">注册</h3>
    <hr>

    <div class="card-body">
        <form id="auth_form" action="{{route("tenancy.register")}}" method="POST" >
            @csrf

            @component('bootstrop4.form.input',['name' => 'account','label' => "用户名:"])
            @endcomponent

            @component('bootstrop4.form.input',['name'=> 'email','label' => '邮箱:','type' => 'email'])
            @endcomponent

            @component('bootstrop4.form.input',['name'=> 'password','label' => '密码:','type' => 'password'])
            @endcomponent

            @component('bootstrop4.form.input',['name'=> 'password_confirmation','label' => '确认密码:','type' => 'password'])
            @endcomponent


            <div class="form-group{{ $errors->has('agree') ? ' is-invalid' : '' }}">
                <div class=" ">
                    <input  type="checkbox"  class=""  name="agree"  >

                    同意 <a
                        {{--href="{{route("tenancy.auth.protocol")}}"--}}
                        style="cursor:not-allowed;"  target="_blank">内控制度流程库使用协议</a>
                </div>
                @if ($errors->has('agree'))
                    <span class="invalid-feedback d-block">
                        <strong>{{ $errors->first('agree') }}</strong>
                    </span>
                @endif




            </div>


            <div class="form-group  ">
                <div class="col-md-12 ">
                    <button type="submit" class="btn btn-primary  login_btn">
                        注册
                    </button>

                    <a class="btn btn-link" href="{{ route('tenancy.login')}}">
                        已有账号？ 去登录
                    </a>
                </div>
            </div>
        </form>
    </div>

@endsection

@section("custom_js")

    <script>
        $("#auth_form").on("submit",function (e) {

            if ($("[name='agree']").prop("checked")) {
                $(this).submit()
                console.log(1);
            }else{
                // event.preventDefault()

                console.log(0);
            }
        });
    </script>
    @endsection

