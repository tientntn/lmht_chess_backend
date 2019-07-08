<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Input, Auth, Validator, Log;

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
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function postLogin() {
        $username = Input::get('username');
        $password = Input::get('password');
        $rules = [
          'username' => 'required',
          'password' => 'required'
        ];
        if (env('APP_ENV') == 'production') {
          $rules['captcha'] = 'required';
        }

        $validator = Validator::make(input::all(), $rules);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        if (env('APP_ENV') == 'production') {
            $data = [
              "secret" => config('services.captcha.secret'),
              "response" =>  Input::get('captcha')
            ];
            $postdata = http_build_query($data);
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $postdata,
              CURLOPT_HTTPHEADER => array(
                  "Content-Type: application/x-www-form-urlencoded",
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $res = json_decode($response);
            if (! $res->success ) {
                return back()->withErrors('Captcha invalid')
                             ->withInput();
            }
        }


        $credentials = ['username' => $username, 'password' => $password ];

        if (Auth::attempt($credentials, Input::has('remember')))
        {
          $user = Auth::user();
          if ($user->role_id == 3 || $user->status == 0) {
            Auth::logout();
            $data['error'] = 'Tài khoản không có quyền truy cập!';
            return view('users.login', $data);
          }
          return redirect()->to('/');
        }else {
          $data['error'] = 'Tài khoản hoặc mật khẩu không đúng!';
          return view('users.login', $data);
        }
    }

    public function logout() {
        Auth::logout();
        return view('users.login');
    }
}
