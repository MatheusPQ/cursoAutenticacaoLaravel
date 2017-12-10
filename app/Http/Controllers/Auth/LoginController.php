<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Illuminate\Http\Request;

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

    //Logica de redirecionamento
    public function redirectTo(){
        //Verifica se tem role admin ou nao
        return \Auth::user()->role == User::ROLE_ADMIN ? '/admin/home' : '/home';
    }

    //Copiou de AuthenticatesUsers
    public function logout(Request $request){
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect($request->is('admin/*') ? '/admin/login' : '/login');
    }

    protected function credentials(Request $request){
        $data = $request->only($this->username(), 'password');
        $userNameKey = $this->usernameKey();
        if($userNameKey != $this->username()){
            $data[$this->usernameKey()] = $data[$this->username()];
            unset($data[$this->username()]);
        }
        return $data;
    }

    //Se tiver mais campos, mexer apenas aqui. Ñ mexer mais em credentials
    protected function usernameKey(){
        $email = \Request::get('email'); //Email ou phone ou cpf
        $validator = \Validator::make([
            'email' => $email
        ], ['email' => 'cpf']);

        if(!$validator->fails()) {
            return 'cpf';
        }

        if(is_numeric($email)){
            return 'phone';
        }

        return 'email';
    }

}
