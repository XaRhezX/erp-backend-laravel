<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Traits\JsonResponse;

class ChangePasswordController extends Controller
{
    use JsonResponse;

    public function passwordResetProcess(UpdatePasswordRequest $request)
    {
        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
    }


    private function updatePasswordRow($request)
    {
        return DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->passwordToken
        ]);
    }

    private function tokenNotFoundError()
    {
        return $this->error(Response::HTTP_UNPROCESSABLE_ENTITY, 'Either your email or token is wrong.');
    }

    private function resetPassword($request)
    {
        $userData = User::whereEmail($request->email)->first();
        $userData->update([
            'password' => bcrypt($request->password)
        ]);
        $this->updatePasswordRow($request)->delete();
        return $this->success($userData, 'Password has been updated.');
    }
}
