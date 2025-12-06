<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordForgotNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors(),
            ], 422);
        }

        $email = $request->email;
        $token = Str::random(60);

        // Remove old token if exists
        DB::table('password_resets')->where('email', $email)->delete();

        // Insert new token
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            // 'created_at' => Carbon::now(),
            // 'created_at' => now(),
            'created_at' => now()->addHours(1),
        ]);

        //mail send manually without notification class

        // Mail::send('mail.password_reset', ['token' => $token], function ($message) use ($email) {
        //     $message->to($email);
        //     $message->subject('Reset your password');
        // });

        $user= User::whereEmail($email)->first();
        Notification::send($user, new PasswordForgotNotification($token));

        return response()->json([
            'message' => 'Password reset link has been sent to your email address.',
        ], 200);

    }
}
