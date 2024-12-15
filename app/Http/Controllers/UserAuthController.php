<?php

namespace App\Http\Controllers;

use App\Models\LoginUser;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    use ResponseTrait;
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'country' => 'nullable|string',
            'email' => 'required|string|email|unique:login_users',
            'password' => 'required|min:8|confirmed',
        ]);
        try {
            $registerUserData['password'] =  Hash::make($registerUserData['password']);
            $registerUserData['status'] = 1;
            $user = LoginUser::create($registerUserData);

            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
            $user->access_token = $token;

        return $this->success( $user, 200, 'User Created Successfully');
    }catch(\Exception $e){
        return $this->ErrorResponse($e->getMessage(), 500);
    }
    }

    public function login(Request $request)
    {

        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        $user = LoginUser::where('email', $loginUserData['email'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return $this->ErrorResponse('Invalid Credentials', 401);
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return $this->success([
            'access_token' => $token,
        ] , 200, 'Login Successfully');
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->currentAccessToken()->delete();
        if(!$logout){
            return $this->ErrorResponse('Logout Failed', 500);
        }
        return $this->success(null , 201 , 'Logout Successfully');
    }
}
