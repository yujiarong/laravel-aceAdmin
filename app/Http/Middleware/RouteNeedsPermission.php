<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;
use App\Models\Access\User\User;
use Illuminate\Support\Facades\Route;
use Auth;
/**
 * Class RouteNeedsRole.
 */
class RouteNeedsPermission
{
    /**
     * @param $request
     * @param Closure $next
     * @param $role
     * @param bool $needsAll
     *
     * @return mixed
     */
    protected $auth;
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }


    public function handle($request, Closure $next, $role, $needsAll = false)
    {
        $roleArr      = explode(';', $role);
        $userRole     = Auth::user()->power;
        $routeName    = Route::currentRouteName();
        if(explode('.',$routeName)[0] == 'user' && !in_array(auth_user()->name, ['余嘉榕','liyuhua']) ){
            return redirect()->back()->withFlashDanger('您没有权限进入此模块');
        }
        $paypal_access = config('access.paypal_access');
        // print_r($paypal_access);
        // print_r(auth_user()->name);
        if(explode('.',$routeName)[0] == 'pay' && !in_array(auth_user()->name, $paypal_access) ){
            return redirect()->back()->withFlashDanger('您没有权限进入支付模块');
        }
        if(!in_array($userRole, $roleArr)){
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                return response()->json(['errCode'=>'101','errMsg'=>'您没有权限进行此操作']);
            }else{
                return redirect()->back()->withFlashDanger('您没有权限进行此操作');
            }
        }
        return $next($request);
    }

}
