<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shared\Domain\ValueObjects\ProductType;
use Modules\Shared\Domain\ValueObjects\Currency;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', array_map(fn(ProductType $enum) => $enum->value, ProductType::cases()))->nullable();
            $table->string('name');
            $table->decimal('price_amount', 10, 2);
            $table->enum('price_currency', array_map(fn(Currency $enum) => $enum->value, Currency::cases()))->nullable();
            $table->datetime('sold_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
