<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Account;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure account belongs to selected user
        $userId = $data['user_id'] ?? $this->getRecord()->user_id;
        $accountId = $data['account_id'] ?? $this->getRecord()->account_id;
        if ($userId && $accountId) {
            $account = Account::find($accountId);
            if (!$account || (int) $account->user_id !== (int) $userId) {
                Notification::make()->title('Selected account does not belong to this user')->danger()->send();
                $this->addError('account_id', 'Selected account does not belong to this user.');
                throw new Halt();
            }
        }

        if (empty($data['currency'])) {
            $user = $userId ? User::find($userId) : null;
            $data['currency'] = $user?->currency ?? (config('currencies.default') ?? 'EUR');
        }

        if (isset($data['amount'])) {
            $data['amount'] = round((float) $data['amount'], 2);
        }

        return $data;
    }
}

