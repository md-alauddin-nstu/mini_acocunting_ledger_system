<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';

    public function apply(float $currentBalance, float $amount): float
    {
        return match ($this) {
            self::DEBIT => $currentBalance + $amount,
            self::CREDIT => $currentBalance - $amount,
        };
    }
}
