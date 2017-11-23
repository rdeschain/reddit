<?php
/**
 * Created by PhpStorm.
 * User: nbkn9dy
 * Date: 11/19/17
 * Time: 7:41 PM
 */

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Closure;
use DB;

class AccessTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        $authorization = explode(" ", $request->header('accessToken'));
        $valid = false;

        if ($authorization) {

            $valid = DB::table('users as u')
                ->join('tokens as t', 't.user_id', 'u.id')
                ->where('token', $authorization[0])
                ->first();
        }

        if(empty($valid))
        {
            return Response::create(['error' => 'Bad token'], 401);
        }
        $request->attributes->add(['userid' => $valid->user_id]);

        return $next($request);
    }

}