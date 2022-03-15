<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuth
{

    public function handle(Request $request, Closure $next)
    {
        $AUTH_USER = 'admin';
        $AUTH_PASS = 'admin';

        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

        $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW']   != $AUTH_PASS
        );

        if ($is_not_authenticated) {

            return response([
                'status' => false,
                'error' => 'Please provide valid credential !'
            ], 401 , [
                'HTTP/1.1 401 Authorization Required',
                'WWW-Authenticate: Basic realm="Access denied"'
            ]);

        }

        return $next($request);
    }
}
