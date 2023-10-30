<?php

namespace Thunk\Verbs\Examples\Bank\Events;

use Thunk\Verbs\Event;
use Thunk\Verbs\Examples\Bank\Models\Account;
use Thunk\Verbs\Examples\Bank\States\AccountState;

class MoneyWithdrawn extends Event
{
    public int $account_id;

    public int $cents = 0;

    public function states(): array
    {
        return [AccountState::load($this->account_id)];
    }

    public function validate(AccountState $state): bool
    {
        return $state->balance_in_cents >= $this->cents;
    }

    public function apply(AccountState $state): void
    {
        $state->balance_in_cents -= $this->cents;
    }

    public function onFire(): void
    {
        [$state] = $this->states();

        Account::find($this->account_id)
            ->update([
                'balance_in_cents' => $state->balance_in_cents,
            ]);
    }
}