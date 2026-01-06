<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\IngestBankEmailAction;
use App\Http\Requests\ProcessBankEmailRequest;
use Illuminate\Http\JsonResponse;

final class BankEmailWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ProcessBankEmailRequest $request, IngestBankEmailAction $action): JsonResponse
    {
        $transaction = $action->execute(
            rawEmail: $request->string('emailBody')->value()
        );

        return response()->json([
            'status' => 'ok',
            'transaction' => $transaction,
        ]);
    }
}
