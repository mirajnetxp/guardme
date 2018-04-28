<?php

namespace Responsive\Http\Controllers\Auth;

use Responsive\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

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


protected function authenticated(Request $request, $user)
{
if(auth()->check() && auth()->user()->id == 1){
            
			return redirect('/admin');
        }
		else
		{
			return redirect('/account');
		}

        
}



public function username()
{
    return 'username';
}


protected function credentials(Request $request)
{
    $usernameInput = trim($request->{$this->username()});
    $usernameColumn = filter_var($usernameInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

    return [$usernameColumn => $usernameInput, 'password' => $request->password];
	
	/* return [$usernameColumn => $usernameInput, 'password' => $request->password, 'active' => 1]; */
}



 protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];
        // Load user from database
        $user = DB::table('users')
				->where('name', $request->{$this->username()})->first();
        
        if ($user && \Hash::check($request->password, $user->password) && $user->admin != 1) {
            $errors = [$this->username() => 'Your account is not active.'];
        }
        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }
        /*return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);*/
			return back()->with('error', 'Invalid login details');
    }



/**
 * Where to redirect users after login.
 *
 * @var string
 */
//protected $redirectTo = '/admin';

/**
 * Create a new controller instance.
 *
 * @return void
 */
public function __construct()
{
    $this->middleware('guest', ['except' => 'logout']);
}
}


