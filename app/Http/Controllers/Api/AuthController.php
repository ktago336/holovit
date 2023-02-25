<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
	
	/**
	* Create a new AuthController instance.
	*
	* @return void
	*/
	public function __construct()
	{
		//全部都要認證 除了login
		$this->middleware('jwt.auth:api', ['except' => ['login']]);
	}


	/**
	* Get a JWT via given credentials.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function login()
	{
	    
	    print_r($_POST);exit;
	    
		// $credentials = request(['email', 'password']);
		$credentials = request(['account', 'password']);



		// if (! $token = auth()->attempt($credentials)) {
		if (! $token = $this->guard()->attempt($credentials)) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		return $this->respondWithToken($token);
	}


	/**
	* Get the authenticated User.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function me()
	{
		// return response()->json(auth()->user());
		return response()->json($this->guard()->user());
	}


	/**
	* Log the user out (Invalidate the token).
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function logout()
	{
		// auth()->logout();
		$this->guard()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}


	/**
	* Refresh a token.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function refresh()
	{
		// return $this->respondWithToken(auth()->refresh());
		return $this->respondWithToken($this->guard()->refresh());
	}


	/**
	* Get the token array structure.
	*
	* @param  string $token
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => $this->guard()->factory()->getTTL() * 60
			// 'expires_in' => auth()->factory()->getTTL()
		]);
	}


	private function guard()
	{
		// return auth()->guard('api');
		return auth('api');
	}
}
