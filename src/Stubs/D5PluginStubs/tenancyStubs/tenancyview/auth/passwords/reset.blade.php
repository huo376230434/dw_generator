@extends('tenancy.auth.authlayout')

@section('auth_content')

                <h3 class="">重置密码</h3>

                @if (session('status'))
                    <div class="alert alert-success " role="alert">
                        {!!  session('status') !!}
                    </div>
                @endif
                <div class="{{session('status') ? "d-none" : ""}}" >
                    <form method="POST" action="{{ route('tenancy.password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group ">
                            <label for="email" class=" col-form-label text-md-right">邮箱:</label>

                            <div class="">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="password" class=" col-form-label text-md-right">密码：</label>

                            <div class="">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="password-confirm" class=" col-form-label text-md-right">确认密码:</label>

                            <div class="">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group  mt-5">
                            <div class="">
                                <button type="submit" class="btn btn-primary btn-block">
                                 重置密码
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

@endsection
