<?php

namespace Modules\ProductManagement\Infrastructure\Persistence;

use Modules\ProductManagement\Domain\Entities\Product;
use App\Models\Product as EloquentProduct;
use App\Models\PaymentInstallment as EloquentPaymentInstallment;
use Modules\ProductManagement\Domain\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\UuidInterface;

class EloquentProductRepository implements ProductRepositoryInterface
{
    /**
     * @return Collection<int,Product>
     */
    public function getAllProductsWithInstallments(): Collection
    {
        return DB::transaction(
            function () {
                return EloquentProduct::with('paymentInstallments')->get();
            }
        );
    }

    public function getProductById(UuidInterface $id): EloquentProduct|null
    {
        return DB::transaction(
            function () use ($id) {
                return EloquentProduct::with('paymentInstallments')->find($id->toString());
            }
        );
    }

    public function createProduct(Product $product): void
    {
        $this->saveProduct($product);
    }

    public function updateProduct(Product $product): void
    {
        $this->saveProduct($product);
    }

    public function deleteProduct(UuidInterface $id): void
    {
        DB::transaction(
            function () use ($id) {
                $eloquentProduct = EloquentProduct::find($id->toString());
                if ($eloquentProduct) {
                    $eloquentProduct->delete();
                }
            }
        );
    }

    public function saveProduct(Product $product): void
    {
        DB::transaction(function () use ($product) {

            $data = [
                'type' => $product->type,
                'name' => $product->name,
                'price_amount' => $product->price->getAmount(),
                'price_currency' => $product->price->getCurrency(),
                'sold_date' => $product->getSoldDateUTC()
            ];

            $eloquentProduct = $product->id
                ? EloquentProduct::findOrFail($product->id)
                : EloquentProduct::create($data);

            if ($product->id) {

                // fill instead for "update" because we want to have dirty method access
                $eloquentProduct->fill($data);

                if ($eloquentProduct->isDirty()) {
                    $eloquentProduct->save();
                    $eloquentProduct->paymentInstallments()->delete();
                }
            }

            if ($eloquentProduct->paymentInstallments()->count() === 0) {
                foreach ($product->getPaymentInstallments() as $installment) {
                    EloquentPaymentInstallment::create([
                        'product_id' => $eloquentProduct->id,
                        'amount' => $installment->getAmount(),
                        'currency' => $installment->getCurrency(),
                        'due_date' => $installment->getDueDateUTC(),
                    ]);
                }
            }
        });
    }
}
