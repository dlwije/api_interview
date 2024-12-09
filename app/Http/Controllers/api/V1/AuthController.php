<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        //except register and login rest function need to have auth key.
        $this->middleware('auth:api', ['except' => ['register','login']]);
        $this->guard = "api";
    }

    public function register(Request $request)
    {
        //validate the request
        $validateRequest = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
        ]);
        if($validateRequest->fails()){

            return response()->json($validateRequest->getMessageBag(), 401);
        }

        try {

            $userResponse = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $token = auth($this->guard)->attempt($request->only('email', 'password'));

            return response()->json([
                'status' => true,
                'message' => 'Successfully registered.',
                'token' => $token,
                'user' => $userResponse
            ], 200);

        }catch (\Exception $e){

            Log::error($e);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again',
                'token' => '',
                'user' => ''
            ], 500);
        }
    }
    public function login(Request $request)
    {
        $validateRequest = Validator::make($request->all(),[
            'email' => 'email|required',
            'password' => 'required',
        ]);
        if($validateRequest->fails()){

            return response()->json($validateRequest->getMessageBag(), 401);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth($this->guard)->attempt($credentials)) {
            return response()->json(['status' => false,'message' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth($this->guard)->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        auth($this->guard)->logout();

        return response()->json([ 'status' => true, 'message' => 'Successfully logged out']);
    }

}
