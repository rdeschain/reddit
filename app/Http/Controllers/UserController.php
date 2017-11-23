<?php
/**
 * Created by PhpStorm.
 * User: nbkn9dy
 * Date: 11/19/17
 * Time: 3:16 PM
 */

namespace App\Http\Controllers;

use App\Token;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $existing = User::where('username', $request->input('username'))->first();

        //user already exists
        if (isset($existing)) {

            return Response::create(['error' => 'Use already exists.'], 403);
        }

        //create user
        $user = new User();
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $token = new Token();
        $token->user_id = $user->id;

        //generate random token
        do{
            $token->token = str_random(25);
        } while(Token::where('token', $token->token)->first() != null);

        $token->save();

        //return token
        return Response::create(['access_token' => $token->token]);

    }

    public function login(Request $request)
    {
        $status = false;
        $user = User::where('username', $request->input('username'))->first();

        if (isset($user)) {

            if (Hash::check($request->input('password'), $user->password)) {

                $status = true;
            }
        }

        return $status ? Response::create(['access_token' => $user->token->token]) : Response::create(['error' => 'Unauthorized. Bad username or password.'], 401);
    }

}