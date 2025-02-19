<?php

declare(strict_types=1);

namespace Modules\ProductManagement\Infrastructure\Persistence;

use Modules\ProductManagement\Domain\Entities\Product;
use Modules\Shared\Domain\ValueObjects\Money;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\ValueObjects\Currency;
use Modules\Shared\Domain\ValueObjects\PaymentInstallment;
use App\Models\Product as EloquentProduct;
use App\Models\PaymentInstallment as EloquentPaymentInstallment;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Modules\ProductManagement\Domain\Repositories\ProductRepositoryMapperInterface;

class EloquentProductRepositoryMapper implements ProductRepositoryMapperInterface
{
    public function toDomainProduct(EloquentProduct $eloquentProduct): Product
    {
        $product = new Product(
            id: Uuid::fromString($eloquentProduct->id),
            name: $eloquentProduct->name,
            type: ProductType::from($eloquentProduct->type),
            price: new Money(
                $eloquentProduct->price_amount,
                Currency::from($eloquentProduct->price_currency)
            ),
            soldDate: new DateTimeImmutable($eloquentProduct->sold_date)
        );

        if ($eloquentProduct->relationLoaded('paymentInstallments')) {
            $paymentInstallments = $eloquentProduct->paymentInstallments->map(
                fn($installment) => $this->toDomainPaymentInstallment($installment)
            );
            $product->setPaymentInstallments($paymentInstallments->all());
        }

        return $product;
    }

    public function toDomainProducts(EloquentCollection $eloquentProducts): Collection
    {
        return $eloquentProducts->map(fn(EloquentProduct $product) => $this->toDomainProduct($product));
    }

    private function toDomainPaymentInstallment(EloquentPaymentInstallment $eloquentInstallment): PaymentInstallment
    {
        return new PaymentInstallment(
            price: new Money(
                $eloquentInstallment->amount,
                Currency::from($eloquentInstallment->currency)
            ),
            dueDate: new DateTimeImmutable($eloquentInstallment->due_date)
        );
    }
}
