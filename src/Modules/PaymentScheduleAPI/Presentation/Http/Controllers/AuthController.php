<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Presentation\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function authorize(Request $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt([
            'email' => $credentials['username'],
            'password' => $credentials['password']
        ])) {
            $user = Auth::user();

            if ($user instanceof User) {

                $user->tokens->each(function ($token) {
                    $token->delete();
                });

                $token = $user->createToken(name: 'ApiAccess', expiresAt: Carbon::now()->addMinutes(15))->plainTextToken;
            }

            return response()->json(['token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
