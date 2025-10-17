<?php

namespace App\Http\Controllers\FrontEnd;

use App\Consts;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $apitoken;
    protected $web_information;
    protected $translates;

    public function index()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('frontend.home');
        }
        //return redirect()->route('frontend.home');

        $translates = $this->translates;
        $web_information = $this->web_information;

        return view('frontend.pages.login',compact('translates','web_information'));
    }


  public function login(LoginRequest $request)
 {
    if (Auth::guard('web')->check()) {
        return redirect()->route('frontend.home');
    }

    $redirect = URL::previous() ?: route('frontend.home');

    $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $credentials = [
        $loginField => $request->email,
        'password' => $request->password,
        'status' => Consts::USER_STATUS['active'],
    ];

    if (Auth::guard('web')->attempt($credentials)) {
        $request->session()->regenerate(); // chống tấn công session fixation
        return redirect($redirect)->with('success', 'Đăng nhập thành công!');
    }

    // return back()
    //     ->withErrors(['email' => 'Tên đăng nhập hoặc mật khẩu không đúng.'])
    //     ->withInput();
        return redirect()->back()->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng.');

 }


    public function logout()
    {
        Auth::guard('web')->logout();

        return redirect()->back();
    }

    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback() {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $finduser = User::where('username', $user->id)
                ->orWhere('email', $user->email)
                ->where('status', 'active')
                ->first();

            if ($finduser) {
                if (!$finduser->username) {
                    $finduser->username = $user->id;
                    $finduser->save();
                }
                
                Auth::login($finduser);
                return redirect('/');
            } else {
                $newUser = User::create(['name' => $user->name, 'email' => $user->email, 'username' => $user->id, 'password' => Hash::make($user->email)]);
                Auth::login($newUser);
                return redirect('/');
            }
        }
        catch(Exception $e) {
            return redirect('auth/google');
        }
    }

    public function redirectToFacebook() {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback() {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();
            $finduser = User::where('username', $user->id)->first();
            if ($finduser) {
                Auth::login($finduser);
                return redirect('/');
            } else {
				$email = $user->email ? $user->email : time().'@gmail.com';
                $newUser = User::create(['name' => $user->name, 'email' => $email, 'username' => $user->id, 'password' => Hash::make($user->email)]);
                Auth::login($newUser);
                return redirect('/');
            }
        }
        catch(Exception $e) {
            return redirect('auth/facebook');
        }
    }

}
