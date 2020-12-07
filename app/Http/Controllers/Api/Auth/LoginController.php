<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class LoginController extends Controller
{
	public function store(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email',
			'password' => 'required',
			'device_name' => 'required'
		]);

		$user = User::where('email', $request->email)->first();

		if(!$user || !Hash::check($request->password, $user->password))
		{
			throw ValidationException::withMessages([
				'email' => ['The provided credentials are incorrect.'],
			]);
		}

		$token = $user->createToken($request->device_name)->plainTextToken;

		return response()->json([
			'id' => $user->id,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'email' => $user->email,
			'token' => $token
		]);
	}
}
