<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
public function login(Request $request)
{
$credentials = $request->validate([
'email' => 'required|email',
'password' => 'required',
]);


if (!Auth::attempt($credentials)) {
return response()->json(['message' => 'Invalid login details'], 401);
}


$user = Auth::user();
$token = $user->createToken('api-token')->plainTextToken;


return response()->json([
'token' => $token,
'user' => $user->load('role'),
]);
}

public function refresh(Request $request)
{
	$user = $request->user();
	if (!$user) {
		return response()->json(['message' => 'Unauthenticated.'], 401);
	}

	// create a new token and revoke the current one
	$current = $user->currentAccessToken();
	$newToken = $user->createToken('api-token')->plainTextToken;
	if ($current) $current->delete();

	return response()->json(['token' => $newToken, 'user' => $user->load('role')]);
}
}