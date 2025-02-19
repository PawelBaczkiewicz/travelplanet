<?php

declare(strict_types=1);

namespace Modules\PaymentScheduleAPI\Domain\Services;

use Modules\PaymentScheduleAPI\Domain\Rules\FallbackPaymentRule;
use Modules\PaymentScheduleAPI\Domain\Rules\PaymentRuleInterface;
use Modules\PaymentScheduleAPI\Domain\Entities\Product;
use Modules\Shared\Application\DTOs\RequestProductInstallmentsDTO;
use Modules\Shared\Domain\Service\CurrencyConverter;

class PaymentScheduleCalculator implements PaymentScheduleCalculatorInterface
{
    private array $rules;

    public function __construct(private CurrencyConverter $converter, array $classNameRules)
    {
        foreach ($classNameRules as $rule) {
            $this->rules[] = new $rule($this->converter);
        }

        // add default fallback rule if not provided
        if (!in_array(FallbackPaymentRule::class, $classNameRules)) {
            $this->rules[] = new FallbackPaymentRule($this->converter);
        }
    }

    public function calculate(RequestProductInstallmentsDTO $requestProductInstallmentsDTO): array
    {
        $product = $requestProductInstallmentsDTO->toEntity();
        $rule = $this->findApplicableRule($product);
        $installments = $rule->calculate($product);

        return array_map(function ($installment) {
            return $installment->jsonSerialize();
        }, $installments);
    }

    private function findApplicableRule(Product $product): ?PaymentRuleInterface
    {
        foreach ($this->rules as $rule) {
            if ($rule->isApplicable($product)) {
                return $rule;
            }
        }
    }
}
