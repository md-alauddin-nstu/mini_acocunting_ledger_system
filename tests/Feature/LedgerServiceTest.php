<?php

use App\Models\Account;
use App\Models\Transaction;
use App\Services\LedgerService;

test('balance increases for a debit transaction', function () {
    $account = Account::create(['name' => 'Cash', 'balance' => 1000.00]);

    $transaction = new Transaction([
        'account_id' => $account->id,
        'type' => 'debit',
        'amount' => 500.00,
    ]);
    $transaction->setRelation('account', $account);

    $service = new LedgerService;
    $updatedAccount = $service->updateBalance($transaction);

    expect($updatedAccount->balance)->toBe(1500.00);
});

test('balance decreases for a credit transaction', function () {
    $account = Account::create(['name' => 'Bank', 'balance' => 2000.00]);

    $transaction = new Transaction([
        'account_id' => $account->id,
        'type' => 'credit',
        'amount' => 750.00,
    ]);
    $transaction->setRelation('account', $account);

    $service = new LedgerService;
    $updatedAccount = $service->updateBalance($transaction);

    expect($updatedAccount->balance)->toBe(1250.00);
});
