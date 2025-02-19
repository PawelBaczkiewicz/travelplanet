<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Shared\Domain\ValueObjects\Currency;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('currency', array_map(fn(Currency $enum) => $enum->value, Currency::cases()))->nullable();
            $table->datetime('due_date')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_installments');
    }
};
