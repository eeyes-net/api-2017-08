<?php

namespace App\Http\Middleware;

use App\Model\Eeyes\Permission\Token;
use Closure;
use Illuminate\Support\Facades\Validator;

class Authenticate
{
    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     *
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
     * @param string $permission
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($request->all(), [
            'token' => 'required|max:190',
        ]);
        if ($validator->fails()) {
            return response(build_api_return([
                'errors' => $validator->errors(),
            ], 400, 'Validate token format failed.'), 400);
        }
        $token = Token::where('token', $request->get('token'))->first();
        if (!$token) {
            return response(build_api_return(null, 403, 'Token not exists.'), 403);
        }

        $result = $token->can($permission);
        if (false === $result) {
            return response(build_api_return(null, 403, 'Forbidden'), 403);
        } elseif (true === $result) {
            return $next($request);
        } else {
            return response(build_api_return(null, 500, $result), 500);
        }
    }
}
