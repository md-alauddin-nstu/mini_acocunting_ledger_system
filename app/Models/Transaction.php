<?php

namespace App\Models;

use App\Services\LedgerService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = ['account_id', 'type', 'amount', 'note'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (Transaction $transaction) {
            app(LedgerService::class)->updateBalance($transaction);
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
