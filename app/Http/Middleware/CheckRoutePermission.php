<?php

namespace App\Http\Middleware;

use App\Http\Traits\PermissionTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckRoutePermission
{
    use PermissionTrait;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $route_permission = config('route_permission');
        $permissions = $this->getPermissionForUserAuthenticated();

        $has_permission = collect($route_permission)
            ->filter(function ($value, $key) use ($permissions, $request){
                $has_permission = false;
                foreach ($value as $url){
                    if (Str::startsWith($request->getRequestUri(), $url)) $has_permission = true;
                }
                return isset($permissions[$key]) && $has_permission;
            });
        if (count($has_permission) || Auth::user()->isAdmin()) return $next($request);
        return response(view('meganet.pages.403'), 403);
    }
}
