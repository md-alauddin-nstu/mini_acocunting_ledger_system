<?php

namespace App\Domains\Account\Strategies;

use App\Domains\Account\Contracts\BalanceUpdateStrategy;
use App\Domains\Account\Models\Account;

class CreditBalanceStrategy implements BalanceUpdateStrategy
{
    /**
     * Credit decreases the balance for asset accounts (Cash, Bank).
     */
    public function calculateNewBalance(Account $account, float $amount): float
    {
        return $account->balance - $amount;
    }
}
