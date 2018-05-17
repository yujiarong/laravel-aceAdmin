<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

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

    public function login(Request $request)
    {
        $this->validateLogin($request);
        $email     = $request->get('email');
        $password  = $request->get('password');
        $count     = User::where('email',$email)->count();
        if($count <1){
            $param              = ["user_name"=>$email,"pwd"=>$password];
            $param['method']    = 'power.user.login.get';
            $result     = json_decode(callOpenSystem($param),true);
            if(empty($result)){
                return redirect()->route('login')->withInput()->withErrors('开放系统连接失败');
            }
            if(isset($result['errCode'])){
                return redirect('/login')->withInput()->withErrors($result['errMsg']);            
            }
            if(!isset($result['userName']) ){
                session()->flush();
                return redirect('/login')->withInput()->withErrors('登陆失败');
            }
            //erp 账号登陆成功 进行手动添加
            if(!$this->store(['email'=>$email,'name'=>$result['userCnName'],'password'=>$password ]) ){
                return redirect('/login')->withInput()->withErrors('手动注册失败');            
            }

        }
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function store($reData){
        $userModel = new User;
        $userModel->email    = $reData['email'];
        $userModel->name     = $reData['name'];
        $userModel->password = bcrypt($reData['password']);
        // $userModel->power    = 'saler';
        return  $userModel->save();      

    }    

}
