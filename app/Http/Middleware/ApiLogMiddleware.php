<?php

namespace App\Http\Middleware;

use App\ApiLog;
use Closure;
use Illuminate\Http\Response;

class ApiLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiLog = new ApiLog();
        $apiLog->path = $request->path();
        $apiLog->method = $request->method();
        $apiLog->ip = (string)$request->ip();
        $apiLog->user_agent = (string)$request->userAgent();
        $apiLog->query = (string)$request->getQueryString();
        $apiLog->body = (string)$request->getContent();
        $apiLog->response_code = 0;
        $apiLog->response_length = 0;
        $apiLog->response_code = 0;
        $apiLog->response_msg = '';
        $apiLog->save();

        /** @var Response $response */
        $response = $next($request);

        $apiLog->response_length = strlen($response->getContent());
        $originalContent = $response->getOriginalContent();
        if (is_array($originalContent)) {
            if (isset($originalContent['code'])) {
                $apiLog->response_code = $originalContent['code'];
            }
            if (isset($originalContent['msg'])) {
                $apiLog->response_msg = $originalContent['msg'];
            }
        }
        $apiLog->save();

        return $response;
    }
}
