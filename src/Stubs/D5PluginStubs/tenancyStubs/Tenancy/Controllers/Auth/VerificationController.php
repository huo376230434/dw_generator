<?php

namespace App\Tenancy\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/tenancy';

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $this->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('tenancy.auth.verify');
    }

    public function __construct(Request $request)
    {

        $this->middleware('tenancy.auth');

        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        if ($request->route('id') != $this->user()->getKey()) {
            throw new AuthorizationException;
        }
        if ($this->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($this->user()->markEmailAsVerified()) {
            event(new Verified($this->user()));
        }

        return redirect("tenancy/auth/success")->with('verified', true);
    }

    public function resend(Request $request)
    {
        if ($this->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $this->user()->sendEmailVerificationNotification();

        return back()->with('resent', true)->with('user',$this->user());
    }


    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return \Auth::guard('tenancy')->user();

    }

    public function success()
    {
        return view('tenancy.auth.success');

    }

}
