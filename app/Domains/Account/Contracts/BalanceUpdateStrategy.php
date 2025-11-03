<?php

namespace App\Domains\Account\Contracts;

use App\Domains\Account\Models\Account;

interface BalanceUpdateStrategy
{
    /**
     * Calculates and returns the new balance.
     *
     * * @param Account $account The account being updated.
     * @param  float  $amount  The transaction amount.
     * @return float The new calculated balance.
     */
    public function calculateNewBalance(Account $account, float $amount): float;
}
