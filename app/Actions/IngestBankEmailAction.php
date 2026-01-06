<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\BankTransaction;
use Illuminate\Support\Carbon;

final class IngestBankEmailAction
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute(string $rawEmail): BankTransaction
    {
        preg_match('/účtu .* číslo (\d+\/\d+)/', $rawEmail, $accountMatch);
        $accountNumber = $accountMatch[1] ?? 'unknown';

        preg_match('/Dostupný zůstatek k (\d{2}\.\d{2}\.\d{4}) v (\d{2}:\d{2})/', $rawEmail, $dateMatch);
        $executedAt = isset($dateMatch[1], $dateMatch[2])
            ? Carbon::createFromFormat('d.m.Y H:i', $dateMatch[1].' '.$dateMatch[2])
            : now();

        if (preg_match('/detaily této úhrady:\s*(.+?)\s*Částka:/s', $rawEmail, $descMatch)) {
            $description = trim($descMatch[1]);
        } else {
            $description = 'Bank transaction';
        }

        preg_match('/Karta:\s([0-9*]+)/', $rawEmail, $cardMatch);
        $card = $cardMatch[1] ?? null;

        preg_match('/Kód transakce:\s(\d+)/', $rawEmail, $codeMatch);
        $transactionCode = $codeMatch[1] ?? null;

        preg_match('/Dostupný zůstatek k .* je ([0-9\s,]+)/', $rawEmail, $balanceMatch);
        $currentBalance = isset($balanceMatch[1])
            ? (float) str_replace([' ', ','], ['', '.'], $balanceMatch[1])
            : null;

        preg_match('/částku\s([0-9\s,]+)\s([A-Z]{3})/i', $rawEmail, $amountMatch);
        $amount = isset($amountMatch[1])
            ? (float) str_replace([' ', ','], ['', '.'], $amountMatch[1])
            : 0;
        if (str_contains($rawEmail, 'se snížil o částku')) {
            $amount = -$amount;
        } elseif (str_contains($rawEmail, 'se zvýšil o částku')) {
            $amount = $amount;
        }
        $currency = $amountMatch[2] ?? 'CZK';

        return BankTransaction::create([
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,
            'transaction_code' => $transactionCode,
            'card' => $card,
            'account_number' => $accountNumber,
            'current_balance' => $currentBalance,
            'executed_at' => $executedAt,
        ]);
    }
}
