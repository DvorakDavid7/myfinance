<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BankTransaction
 *
 * @property-read int $id
 * @property-read float $amount
 * @property-read string $currency
 * @property-read string|null $description
 * @property-read string|null $transaction_code
 * @property-read string|null $card
 * @property-read string|null $account_number
 * @property-read float|null $current_balance
 * @property-read \Illuminate\Support\Carbon|null $executed_at
 * @property-read \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Support\Carbon|null $updated_at
 */
final class BankTransaction extends Model
{
    protected $fillable = [
        'amount',
        'currency',
        'description',
        'transaction_code',
        'card',
        'account_number',
        'current_balance',
        'executed_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'executed_at' => 'datetime',
    ];
}
