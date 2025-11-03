<?php

namespace App\Domains\Account\Strategies;

use App\Domains\Account\Contracts\BalanceUpdateStrategy;
use App\Domains\Account\Models\Account;

class DebitBalanceStrategy implements BalanceUpdateStrategy
{
    /**
     * Debit increases the balance for asset accounts (Cash, Bank).
     */
    public function calculateNewBalance(Account $account, float $amount): float
    {
        return $account->balance + $amount;
    }
}
