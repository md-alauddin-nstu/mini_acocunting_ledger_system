<?php

namespace App\Domains\Account;

use App\Domains\Account\Contracts\BalanceUpdateStrategy;
use App\Domains\Account\Strategies\CreditBalanceStrategy;
use App\Domains\Account\Strategies\DebitBalanceStrategy;

class BalanceStrategyFactory
{
    protected array $strategies = [
        'debit' => DebitBalanceStrategy::class,
        'credit' => CreditBalanceStrategy::class,
    ];

    public function getStrategy(string $type): BalanceUpdateStrategy
    {
        $strategyClass = $this->strategies[strtolower($type)] ?? null;

        if (! $strategyClass) {
            throw new \InvalidArgumentException("Invalid transaction type: {$type}");
        }

        return app($strategyClass);
    }
}
