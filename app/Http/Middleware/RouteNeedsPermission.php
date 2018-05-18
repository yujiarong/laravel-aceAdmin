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
        return $next($request);
    }

}
