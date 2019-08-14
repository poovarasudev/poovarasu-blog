<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\ApiLoginValidation;
use App\Http\Resources\ApiLoginResponse;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(ApiLoginValidation $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
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
//        return $token = JWTAuth::getToken();
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

    public function getPosts()
    {
        $users = User::all();
        $posts = Post::all();
        $result = $users->map(function ($item) use ($posts) {
            $post = array_values($posts->where('user_id', '=', $item->id)->toArray());
            return collect($item)->merge(['posts' => $post]);
        })->toArray();

        return response()->json($result);
    }
}
