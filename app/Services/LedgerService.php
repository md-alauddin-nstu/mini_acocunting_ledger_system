<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class LedgerService
{
    public function updateBalance(Transaction $transaction): Account
    {
        $account = $transaction->account;
        if (! $account) {
            throw new \Exception("Account not found for transaction id: {$transaction->id}");
        }

        $amount = $transaction->amount;
        $newBalance = $account->balance;

        switch ($transaction->type) {
            case 'debit':
                $newBalance += $amount;
                break;
            case 'credit':
                $newBalance -= $amount;
                break;
            default:
                throw new \InvalidArgumentException("Invalid transaction type: {$transaction->type}");
                break;
        }

        return DB::transaction(function () use ($account, $newBalance) {
            $account->balance = $newBalance;
            $account->save();

            return $account;
        });
    }

    public function addTransaction(int $accountId, string $type, float $amount, ?string $note = null): Transaction
    {
        if (! in_array($type, ['debit', 'credit'])) {
            throw new \InvalidArgumentException("Invalid transaction type: {$type}");
        }

        $transaction = Transaction::withoutEvents(function () use ($accountId, $type, $amount, $note) {
            return Transaction::create([
                'account_id' => $accountId,
                'type' => $type,
                'amount' => $amount,
                'note' => $note,
            ]);
        });

        $this->updateBalance($transaction);

        return $transaction;
    }

    public function getLedgerReport(int $accountId): array
    {
        $account = Account::findOrFail($accountId);

        $report = $account->transactions()
            ->selectRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debit')
            ->selectRaw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credit')
            ->first();

        return [
            'account_name' => $account->name,
            'total_debit' => (float) $report->total_debit,
            'total_credit' => (float) $report->total_credit,
            'current_balance' => (float) $account->balance,
        ];
    }
}
