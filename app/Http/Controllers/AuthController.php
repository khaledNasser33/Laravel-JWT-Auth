<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','reset-password-request']]);
    }

    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    // public function resend(Request $request){
    //     if($request->user()->hasVerifiedEmail()){
    //         return response(['message'=>'Already verified !!!']);
    //     }
    //     $request->user()->sendEmailVerificationNotification();
    //     if($request->wantsJson()){
    //         return response(['message'=>'Email sent']);
    //     }

    //     return back()->with('resent',true);
    // }

    // public function verify(Request $request){
    //     auth()->Auth::loginUsingId($request->route('id'));

    //     if($request->route('id') != $request->user()->getKey()){
    //         throw new AthorizationException;
    //     }

    //     if($request->user()->hasVerifiedEmail()){
    //         return response(['message'=>'Already verified !!!']);
    //     }

    //     if($request->user()->markEmailAsVerified()){
    //         event(new Verified($request->user()));

    //     }

    //     return response(['message'=>'Successfully verified']);
    // }

    // public function resetPassword(Request $request){
    //     $request->validate(['email' => 'required|email']);

    //     $status = Password::sendResetLink(
    //     $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //             ? back()->with(['status' => __($status)])
    //             : back()->withErrors(['email' => __($status)]);
    // }
}
