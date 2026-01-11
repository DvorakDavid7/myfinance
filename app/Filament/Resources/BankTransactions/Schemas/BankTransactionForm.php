<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankTransactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class BankTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('CZK'),
                TextInput::make('description'),
                TextInput::make('transaction_code'),
                TextInput::make('card'),
                TextInput::make('current_balance')
                    ->numeric(),
                TextInput::make('account_number'),
                DateTimePicker::make('executed_at'),
            ]);
    }
}
