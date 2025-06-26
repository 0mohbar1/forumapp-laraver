<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $registerRequest)
{
    try {
        $userData = $registerRequest->validated();
        $userData['password'] = Hash::make($userData['password']);

        $user = User::create($userData);
        $token = $user->createToken('ShopApp')->plainTextToken;

        return response([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
            ],
            'token' => $token
        ], 201);
    } catch (\Exception $e) {
        return response([
            'error' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage()
        ], 500);
    }
}
public function login(LoginRequest $loginRequest){
    $loginRequest->validated();
    $user=User::whereUsername($loginRequest->username)->first();
    if(!$user ||!Hash::check($loginRequest->password,$user->password)){
        return response(['message'=>'invalid'],422);
    }
            $token = $user->createToken('ShopApp')->plainTextToken;
return response([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
            ],
            'token' => $token
        ], 200);
}
public function logout(Request $request)
{
    try {
        // التحقق من وجود مستخدم مصادق عليه
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthorized: No authenticated user found'
            ], 401);
        }

        // حذف التوكن الحالي
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    } catch (\Exception $e) {
        // تسجيل الخطأ للتحليل (اختياري)
        Log::error('Logout failed: ' . $e->getMessage());

        return response()->json([
            'message' => 'Failed to logout, please try again'
        ], 500);
    }
}
public function getInfo(Request $request){
        return response()->json([
        'name' => $request->user()->name,
        'username' => $request->user()->username,
        'email' => $request->user()->email,
    ]);
}
}
