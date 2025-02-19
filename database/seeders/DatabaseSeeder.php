<?php

namespace Database\Seeders;

use App\Models\PaymentInstallment;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Modules\PaymentScheduleAPI\Domain\Services\PaymentScheduleCalculator;
use Modules\PaymentScheduleAPI\Domain\Rules\StandardPaymentRule;
use Modules\PaymentScheduleAPI\Domain\Rules\PremiumPaymentRule;
use Modules\PaymentScheduleAPI\Domain\Rules\StudentPaymentRule;
use Modules\PaymentScheduleAPI\Domain\Rules\JunePaymentRule;
use Modules\Shared\Application\DTOs\RequestProductInstallmentsDTO;
use Modules\Shared\Domain\Service\CurrencyConverter;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class
        ]);

        $products = Product::factory(5)->create();

        $calculator = new PaymentScheduleCalculator(
            converter: new CurrencyConverter(),
            classNameRules: [
                JunePaymentRule::class,
                StandardPaymentRule::class,
                PremiumPaymentRule::class,
                StudentPaymentRule::class,
            ]
        );

        foreach ($products as $product) {

            $installments = $calculator->calculate(new RequestProductInstallmentsDTO(
                type: $product->type->value,
                priceAmount: $product->price_amount,
                priceCurrency: $product->price_currency->toString(),
                soldDate: $product->sold_date
            ));

            foreach ($installments as $installment) {
                PaymentInstallment::create([
                    'product_id' => $product->id,
                    'amount' => $installment['amount'],
                    'currency' => $installment['currency'],
                    'due_date' => $installment['dueDate'],
                ]);
            }
        }
    }
}
