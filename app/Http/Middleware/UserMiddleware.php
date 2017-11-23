<?php
/**
 * Created by PhpStorm.
 * User: nbkn9dy
 * Date: 11/19/17
 * Time: 7:16 PM
 */

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Closure;
use DB;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {

        if ($request->input('username') == null || $request->input('password') == null) {
            return Response::create(['error' => 'Missing "username" or "password" data'], 422);
        }

        return $next($request);
    }

}