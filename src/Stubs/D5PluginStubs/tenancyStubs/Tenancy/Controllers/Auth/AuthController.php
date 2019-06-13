<?php

namespace App\Tenancy\Controllers\Auth;

use App\Lib\Common\Dictionary\JsonResult;
use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Json;

class AuthController extends BaseAuthController
{

    protected $redirectTo = "/";
    /**
     * Show the login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        return view('tenancy.auth.login');//在需要有登录注册功能时用这个模板
        return view('tenancy.login');
    }



    public function postLogin(Request $request)
    {
        $credentials = $request->only([$this->account(), 'password']);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->account()   => 'required',
            'password'          => 'required',
        ]);

        if ($validator->fails()) {

            return back()->withInput()->withErrors($validator);
        }

        if ($this->guard()->attempt($credentials)) {
            return $this->sendLoginResponse($request);
        }

        return back()->withInput()->withErrors([
            $this->account() => $this->getFailedLoginMessage(),
        ]);
    }

    protected function account()
    {
        return 'account';
    }


    protected function guard()
    {
        return Auth::guard('tenancy');
    }


    /**
     * User logout.
     *
     * @return Redirect
     */
    public function getLogout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(config('tenancy.route.prefix'));
    }

}
