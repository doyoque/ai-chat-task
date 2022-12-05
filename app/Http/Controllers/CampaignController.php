<?php

namespace App\Http\Controllers;

use App\Models\{
    PurchaseTransaction,
    Voucher,
};
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\ImageStoreRequest;
use DateTime;

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
        $eligibleResponse = [
            "message" => "eligible",
            "code" => 200,
        ];

        $userId = $request->get("user_id");

        $allocatedVoucher = $this->voucherCheck($userId, true);
        if ($allocatedVoucher) {
            return response([
                "message" => "You already recieve voucher code",
                "code" => 422,
            ], 422);
        }

        $validateVoucher = $this->voucherCheck($userId);
        if ($validateVoucher) {
            $currentTime = new DateTime();
            $futureTime = new DateTime(date("Y-m-d H:i:s", strtotime($validateVoucher->updated_at)));
            $interval = $futureTime->diff($currentTime);

            if ($currentTime > $futureTime) {
                $validateVoucher->updated_at = null;
                $validateVoucher->customer_id = null;
                $validateVoucher->save();

                $this->updateVoucher($userId);
                return response($eligibleResponse, 200);
            }

            return response([
                "message" => "please complete photo submission before 10 minutes",
                "time_remaining" => $interval->format("%i minutes, %s seconds"),
                "code" => 422,
            ], 422);
        }

        $transactions = PurchaseTransaction::where([
            [ "customer_id", "=", $userId ],
            [ "transaction_at", ">=", Carbon::now()->subDays(30) ],
        ]);

        if (count($transactions->get()) >= 3 && $transactions->sum("total_spent") >= 100.00) {
            $this->updateVoucher($userId);
            return response($eligibleResponse, 200);
        }

        return response([
            "message" => "not eligible",
            "code" => 200,
        ], 200);
    }

    /**
     * Photo check.
     *
     * @param App\Http\Requests\ImageStoreRequest $request
     * @return json
     */
    public function photoCheck(ImageStoreRequest $request)
    {
        $request->validated();
        $userId = $request->get("customer_id");
        $allocatedVoucher = $this->voucherCheck($userId, true);
        if ($allocatedVoucher) {
            return response([
                "message" => "You already recieve voucher code",
                "code" => 422,
            ], 422);
        }

        // $request->file("image")->store("image", "public");

        $imgRecognition = true;
        $validVoucher = true;
        $voucher = $this->voucherCheck($userId);
        if ($voucher) {
            $currentTime = new DateTime();
            $futureTime = new DateTime(date("Y-m-d H:i:s", strtotime($voucher->updated_at)));
            if ($currentTime > $futureTime) {
                $voucher->updated_at = null;
                $voucher->customer_id = null;
                $voucher->save();
                $validVoucher = false;
            }
        } else {
            $validVoucher = false;
        }

        if ($imgRecognition && $validVoucher) {
            $voucher->image_recognition = $imgRecognition;
            $voucher->save();

            return response([
                "message" => "Here's your voucher",
                "voucher" => $voucher->code,
                "code" => 200,
            ], 200);
        }

        return response([
            "message" => "The photo is invalid or submission reach 10 minutes",
            "code" => 422,
        ], 422);
    }


    /**
     * Check voucher is allocated.
     *
     * @param int $userId
     * @param bool $imgRecognition
     * @return object
     */
    private function voucherCheck($userId, $imgRecognition = false)
    {
        return Voucher::where([
            [ "customer_id", "=", $userId ],
            [ "updated_at", "!=", null ],
            [ "image_recognition", "=", $imgRecognition ],
        ])->first();
    }

    /**
     * Update voucher to eligible customer.
     *
     * @param int $userId
     * @return object
     */
    private function updateVoucher($userId)
    {
        $voucher = Voucher::where([
            [ "updated_at", "=", null ],
            [ "customer_id", "=", null ],
        ])->first();

        $voucher->customer_id = $userId;
        $voucher->updated_at = now()->addMinutes(10);
        $voucher->save();

        return $voucher;
    }
}
