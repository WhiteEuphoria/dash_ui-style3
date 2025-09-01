<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Account;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure account belongs to selected user
        $userId = $data['user_id'] ?? null;
        $accountId = $data['account_id'] ?? null;
        if ($userId && $accountId) {
            $account = Account::find($accountId);
            if (!$account || (int) $account->user_id !== (int) $userId) {
                Notification::make()->title('Selected account does not belong to this user')->danger()->send();
                $this->addError('account_id', 'Selected account does not belong to this user.');
                throw new Halt();
            }
        }

        // Fill currency from user when missing
        if (empty($data['currency'])) {
            $user = $userId ? User::find($userId) : null;
            $data['currency'] = $user?->currency ?? (config('currencies.default') ?? 'EUR');
        }

        // Normalize amount to 2 decimals
        if (isset($data['amount'])) {
            $data['amount'] = round((float) $data['amount'], 2);
        }

        return $data;
    }
}

