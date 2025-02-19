<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Infrastructure\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RefreshTokenMiddleware
{
    public function handle($request, \Closure $next)
    {
        $user = Auth::user();

        if ($user instanceof User) {

            $token = $user->tokens->firstWhere('name', 'ApiAccess');

            if ($token) {
                $expiresAt = Carbon::parse($token->expires_at);

                if ($expiresAt->isFuture()) {
                    $token->update([
                        'expires_at' => Carbon::now()->addMinutes(5),
                    ]);
                }
            }
        }

        return $next($request);
    }
}
