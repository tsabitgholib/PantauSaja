<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Account;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->adjustBalance($transaction, 'apply');
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Revert old balance
        $oldTransaction = new Transaction($transaction->getOriginal());
        $this->adjustBalance($oldTransaction, 'revert');

        // Apply new balance
        $this->adjustBalance($transaction, 'apply');
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->adjustBalance($transaction, 'revert');
    }

    /**
     * Adjust account balances based on transaction type and action.
     */
    private function adjustBalance(Transaction $transaction, string $action): void
    {
        $account = Account::find($transaction->account_id);
        if (!$account) return;

        $multiplier = ($action === 'apply') ? 1 : -1;

        if ($transaction->type === 'income') {
            $account->balance += ($transaction->amount * $multiplier);
            $account->save();
        } elseif ($transaction->type === 'expense') {
            $account->balance -= ($transaction->amount * $multiplier);
            $account->save();
        } elseif ($transaction->type === 'transfer') {
            // Source account (decrement for apply, increment for revert)
            $account->balance -= ($transaction->amount * $multiplier);
            $account->save();

            // Destination account (increment for apply, decrement for revert)
            $destAccount = Account::find($transaction->to_account_id);
            if ($destAccount) {
                $destAccount->balance += ($transaction->amount * $multiplier);
                $destAccount->save();
            }
        }
    }
}
