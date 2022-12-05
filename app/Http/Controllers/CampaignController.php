<?php

namespace App\Http\Controllers;

use App\Models\PurchaseTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Eligible validation.
     * 
     * @param Illuminate\Http\Request $request
     * @return json
     */
    public function eligibleCheck(Request $request)
    {
        $transactions = PurchaseTransaction::where([
            [ "customer_id", "=", $request->get("user_id") ],
            [ "transaction_at", ">=", Carbon::now()->subDays(30) ],
        ]);

        if (count($transactions->get()) >= 3 && $transactions->sum("total_spent") >= 100.00) {
            return response([
                "message" => "eligible",
                "code" => 200,
            ], 200);
        }
        
        return response([
            "message" => "not eligible",
            "code" => 200,
        ], 200);
    }
}
