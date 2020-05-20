<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use Exception;
use App\User;
use DB;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {
          $user = Socialite::driver('google')->user();
          $finduser = User::where('google_id',$user->id)->get();

      if($finduser->count()>0)
      {
            $user_array = array('name'=>$user->name,'email'=>$user->email,'google_id'=>$user->id);
           Auth::loginUsingId($finduser[0]->id);
           return redirect('/home'); 
      }
      else{
            $newuser = new User();
            $newuser->name=$user->name;
            $newuser->email=$user->email;
            $newuser->google_id = $user->id;
            $newuser->save();
             
             Auth::loginUsingId($newuser->id);
             return redirect('/home');
      }
             
    }   
 
}
