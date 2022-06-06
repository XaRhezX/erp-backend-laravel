<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use stdClass;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    use JsonResponse;

    public function handle(Request $request)
    {
        try {
            JWTAuth::invalidate($request->bearerToken());
            return $this->success(new stdClass(),'User logged out successfully', 200);
        } catch (JWTException $exception) {
            return $this->error(Response::HTTP_INTERNAL_SERVER_ERROR, 'Sorry, the user cannot be logged out');
        }
    }
}
