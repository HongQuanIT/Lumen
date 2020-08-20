<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;
use App\Http\Controllers\Controller;
use Exception;


class AuthController extends Controller 
{
     /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        
        $a = $request->all();
        try {
            $user = User::create(
                [
                    "name"=>$a['name'],
                    "email"=>$a['email'],
                    "password"=>app('hash')->make($a['password']),
                ]
            );
            //return successful response
            return response()->json([
                'status' => true,
                'code' => 201,
                'message' => "User Registration Successfully!",
                'data' => $user,
                'errors' => null,
            ], 201);

        } catch (Exception $e) {
            //return error message
            return response()->json([
                'status' => false,
                'code' => 409,
                'message' => "User Registration Failed!",
                'errors' => $e->getMessage(),
            ], 409);
        }

    }
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required|string'
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $validator = $this->validate($request, $rules, $customMessages);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return $this->setMessage("Wrong email or password. Please try again!")
                        ->setCode(401)
                        ->setStatusCode(401)
                        ->sendErrorData();
        }
        return $this->respondWithToken($token);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = JWTAuth::getToken();//get token
        //$payload = JWTAuth::payload();//check payload ( decode ) 
        //$check = JWTAuth::check();//check login
        //return $token;
        try {
            $token = JWTAuth::refresh($token);//refresh token
        } catch (Exception $e) {
            return $this->setMessage("Token is invalid!")
                ->setCode(443)
                ->setStatusCode(443)
                ->sendErrorData();
        }
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Refresh token successfully!",
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();// logout current user.
        //logout all devices. Let's use redis to handle.
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Logout successfully!",
            'errors' => null,
        ], 200);
    }
}