<?php

namespace App\Tenancy\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterVerify;
use App\Model\SysAccount;
use App\Model\TenancyUser;
use Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/tenancy/auth/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'account' => ['required', 'string', 'max:255','unique:tenancy_users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenancy_users'],
            'password' => ['required', 'string','confirmed'],
            'agree' => ['accepted']
        ],[
            'agree.accepted' => "请同意艾图协议"
        ]);
    }
//
//
//    public function register(Request $request)
//    {
//
//        $this->validator($request->all())->validate();
//
//        //验证邮箱是否存在，如有未激活的删掉以前的记录
//
//        $user = TenancyUser::where('account',$request->account)->whereNull("email_verified_at")->first();
//        if($user){
//            TenancyUser::where('account',$request->account)->delete();
//        }
//
//        $this->sendAuthEmail($user);
//
//        return $this->registered($request, $user)
//            ?: redirect($this->redirectPath());
//
//    }


    public function sendAuthEmail($user)
    {

        if($user && $user->account) {
//            发送邮件
            $code = base64_encode($user->account);
            $url = url('/tenancy/auth/register/email_verify'.'/'.$code.'/'.time().'/'.strtoupper(md5(config('app.name').$code)));
            $subject = '欢迎加入' . config('app.name');
            Log::info(date('Y-m-d H:i:s').'注册用户发送邮件:'.$user->email);
            Mail::to($user->email)->send(new RegisterVerify($subject,$url));
        }
    }



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return TenancyUser::create([
            'account' => $data['account'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }


    public function showRegistrationForm()
    {
//        dd(33);
        return view('tenancy.auth.register');
    }


    public function protocol()
    {

        return view("tenancy.auth.protocol");

    }


//    public function redirectTo()
//    {
//        return route("tenancy.login");
//    }

    protected function guard()
    {
        return Auth::guard("tenancy");
    }




}
