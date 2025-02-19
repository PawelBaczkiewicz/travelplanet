<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PaymentScheduleAPI\Domain\Rules\JunePaymentRule;
use Modules\PaymentScheduleAPI\Domain\Rules\PremiumPaymentRule;
use Modules\PaymentScheduleAPI\Domain\Rules\StandardPaymentRule;
use Modules\PaymentScheduleAPI\Domain\Rules\StudentPaymentRule;
use Modules\PaymentScheduleAPI\Domain\Services\PaymentScheduleCalculatorInterface;
use Modules\PaymentScheduleAPI\Domain\Services\PaymentScheduleCalculator;
use Modules\PaymentScheduleAPI\Infrastructure\Services\ApiVersionService;
use Modules\Shared\Domain\Service\CurrencyConverter;

class ApiVersionMiddleware
{
    private ApiVersionService $apiVersionService;

    public function __construct(ApiVersionService $apiVersionService)
    {
        $this->apiVersionService = $apiVersionService;
    }
    public function handle(Request $request, Closure $next)
    {
        $apiVersion = $this->apiVersionService->getApiVersion();

        if ($apiVersion === 'v2') {

            if (Auth::check() && Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized');
            }

            app()->scoped(PaymentScheduleCalculatorInterface::class, function ($app) {
                return new PaymentScheduleCalculator(
                    converter: $app->make(CurrencyConverter::class),
                    classNameRules: [
                        JunePaymentRule::class,
                        StandardPaymentRule::class,
                        PremiumPaymentRule::class,
                        StudentPaymentRule::class,
                    ]
                );
            });
        } else {
            app()->scoped(PaymentScheduleCalculatorInterface::class, function ($app) {
                return new PaymentScheduleCalculator(
                    converter: $app->make(CurrencyConverter::class),
                    classNameRules: [
                        JunePaymentRule::class,
                        StandardPaymentRule::class,
                        PremiumPaymentRule::class,
                    ]
                );
            });
        }

        return $next($request);
    }
}
