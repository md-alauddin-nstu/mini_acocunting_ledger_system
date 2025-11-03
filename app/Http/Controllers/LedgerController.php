<?php

namespace App\Http\Controllers;

use App\Domains\Account\Facades\Ledger;
use Illuminate\Http\JsonResponse;

class LedgerController extends Controller
{
    public function report(int $accountId): JsonResponse
    {
        try {
            $reportData = Ledger::getLedgerReport($accountId);

            return response()->json([
                'status' => 'success',
                'report' => $reportData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account not found or report generation failed.',
            ], 404);
        }
    }
}
