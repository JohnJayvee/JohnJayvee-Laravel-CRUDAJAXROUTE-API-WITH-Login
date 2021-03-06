<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**

     * Create a new controller instance.

     *

     * @return void

     */
    // custom username
    public function username()
    {
        $field = (filter_var(request()->username, FILTER_VALIDATE_EMAIL) || !request()->username) ? 'email' : 'username';
        request()->merge([$field => request()->username]);
        return $field;
    }

    public function login(Request $request)

    {

        $input = $request->all();
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (Auth::attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
            return redirect()->route('home');
        } else {

            return redirect()->back()->withErrors(['message' => 'These credentials do not match our records']);

            // return redirect()->back()->with('message', 'Invalid username or email and password combination');
            // $request->session()->flash('message', __('auth.failed'));
            // return redirect()->back();

        }
    }
}
