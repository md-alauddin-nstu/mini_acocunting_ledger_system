<?php

use App\Domains\Account\Facades\Ledger;
use App\Domains\Account\Models\Account;

test('facade and eloquent event update balance atomically', function () {
    $account = Account::create(['name' => 'Test Account', 'balance' => 0.00]);

    Ledger::addTransaction($account->id, 'debit', 500.00, 'Test Deposit');

    $this->assertDatabaseHas('transactions', [
        'account_id' => $account->id,
        'amount' => 500.00,
        'type' => 'debit',
    ]);

    $updatedAccount = $account->refresh();
    expect($updatedAccount->balance)->toBe(500.00);
});

test('ledger report endpoint returns correct totals and balance', function () {
    $cash = Account::create(['name' => 'Cash', 'balance' => 0.00]);

    Ledger::addTransaction($cash->id, 'debit', 1000.00, 'Initial Deposit');
    Ledger::addTransaction($cash->id, 'credit', 200.00, 'Expense 1');
    Ledger::addTransaction($cash->id, 'debit', 500.00, 'Re-deposit');
    Ledger::addTransaction($cash->id, 'credit', 300.00, 'Expense 2');

    $response = $this->get("/ledger/report/{$cash->id}");

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'report' => [
                'account_name' => 'Cash',
                'total_debit' => 1500.00,
                'total_credit' => 500.00,
                'current_balance' => 1000.00,
            ],
        ]);
});

test('ledger report returns 404 for a non-existent account', function () {
    $response = $this->get('/ledger/report/999');

    $response->assertStatus(404)
        ->assertJsonFragment([
            'message' => 'Account not found or report generation failed.',
        ]);
});
