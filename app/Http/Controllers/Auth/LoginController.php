<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    use JsonResponse;

    public function handle(Request $request)
    {
        $this->validate($request,            [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return $this->error(Response::HTTP_UNAUTHORIZED, ['error' => ['Invalid Email or Password']]);
        }
        $user = JWTAuth::user();
        $data = collect([
            'token' => $jwt_token
        ]);
        return $this->success($data, "Welcome, " . $user->name);
    }
}
