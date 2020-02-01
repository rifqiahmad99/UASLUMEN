<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthUsersController extends Controller{
 /**
  * Store a new user.
  *		
  * @param Request $request
  * @return Response
  */	
	public function register(Request $request){
		$this->validate($request, [
			'nama' => 'required|string',
			'username' => 'required|string',		
			'password' => 'required|confirmed',
		]);

		$input = $request->all();
		$validationRules = [
			'nama' => 'required|string',
			'username' => 'required|string',		
			'password' => 'required|confirmed',
		];

		$validator = \Validator::make($input, $validationRules);

		if($validator->fails()){
			return response()->json($validator->errors(), 400);
		}

		$user = new User;
		$user->nama = $request->input('nama');
		$user->username = $request->input('username');
		$plainPassword = $request->input('password');
		$user->password = app('hash')->make($plainPassword);
		$user->save();

		return response()->json($user, 200);
	}
	
 /**
  * Get a JWT via given credentials.
  *		
  * @param Request $request
  * @return Response
  */
	public function login(Request $request){
		$input = $request->all();
		$validationRules = [
			'username' => 'required|string',
			'password' => 'required|string',
		 ];

		$validator = \Validator::make($input, $validationRules);
		
		if($validator->fails()){
			return response()->json($validator->errors(), 400);
		}	

		$credentials = $request->only(['username', 'password']);

		if(! $token = Auth::attempt($credentials)){
			return response()->json(['message' => 'Unauthorized'], 401);
		}	

		return response()->json([
			'token' => $token,
			'token_type' => 'bearer',
			'expires_in' => Auth::factory()->getTTL()*60
		], 200);
	}
}

