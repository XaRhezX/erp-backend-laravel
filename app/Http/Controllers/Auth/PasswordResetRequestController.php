<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Mail\SendMail;
use App\Traits\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use stdClass;

class PasswordResetRequestController extends Controller
{
    use JsonResponse;

    public function sendPasswordResetEmail(Request $request)
    {
        $this->validate($request,            [
            'email' => 'required|email|exists:users,email',
        ]);
        // If email does not exist
        if (!$this->validEmail($request->email)) {
            return $this->error(Response::HTTP_NOT_FOUND, 'Email does not exist.');
        } else {
            // If email exists
            return $this->success(new stdClass, 'Check your inbox, we have sent a link to reset email.');
            $this->sendMail($request->email);
        }
    }

    public function sendMail($email)
    {
        $token = $this->generateToken($email);
        Mail::to($email)->send(new sendMail($token));
    }
    public function validEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    public function generateToken($email)
    {
        $isOtherToken = DB::table('password_resets')->where('email', $email)->first();
        if ($isOtherToken) {
            return $isOtherToken->token;
        }
        $token = Str::random(80);;
        $this->storeToken($token, $email);
        return $token;
    }

    public function storeToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }
}
