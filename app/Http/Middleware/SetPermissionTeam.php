<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetPermissionTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $company_id = Cookie::get('company_id');
        if (is_null($company_id)) {
            return redirect()->route('select-company');
        }

        $company = Auth::user()->companies()->where('company_id', $company_id)->first();
        if (is_null($company)) {
            return redirect()->route('select-company');
        }

        setPermissionsTeamId($company_id);
        return $next($request);
    }
}
