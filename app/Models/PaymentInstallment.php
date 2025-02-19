<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Ramsey\Uuid\Uuid;

class PaymentInstallment extends Model
{
    use HasUuids;
    public $timestamps = false;
    protected $table = 'payment_installments';

    protected $fillable = [
        'product_id',
        'amount',
        'currency',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'string'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Uuid::uuid4()->toString();
            }
        });
    }
}
