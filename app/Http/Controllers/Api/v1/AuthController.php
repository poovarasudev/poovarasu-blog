<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Api\v1\ApiLoginValidation;
use App\Http\Resources\ApiLoginResponse;
use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use mysql_xdevapi\Collection;
use test\Mockery\Fixtures\MethodWithHHVMReturnType;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('JWTAuthentication', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param ApiLoginValidation $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(ApiLoginValidation $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Please enter a valid credentials'], 401);
        }

        return (new ApiLoginResponse(auth()->user()))->response()->header('access_token', "Bearer ".$token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = auth()->refresh();
        return $this->respondWithToken($token)->header('access_token', "Bearer ".$token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
