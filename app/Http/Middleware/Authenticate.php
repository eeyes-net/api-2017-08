<?php

namespace App\Http\Middleware;

use App\Library\Eeyes\EeyesAdmin;
use Closure;

class Authenticate
{
    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (!$request->has('token')) {
            return response('Token must be provided', 400);
        }
        $token = $request->input('token');
        $token_result = EeyesAdmin::token($token);
        if (!$token_result['username']) {
            return response($token_result['msg'], 403);
        }
        $username = $token_result['username'];
        $permission = EeyesAdmin::permission($username, $permission);
        if ($permission['can'] !== true) {
            return response($permission['msg'], 403);
        }
        return $next($request);
    }
}
