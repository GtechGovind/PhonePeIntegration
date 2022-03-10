<?php

namespace App\Http\Controllers\Api\PhonePe;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PhonePeStatusController extends Controller
{

    public $salt_key;
    public $salt_index;
    public $x_client_id;

    public function __construct()
    {
        $this->salt_key = env('PHONEPAY_SLAT_KEY');
        $this->salt_index = env("PHONEPAY_SLAT_INDEX");
        $this->x_client_id = env("PHONEPAY_CLIENT_ID");
    }

    public function getPaymentStatus($order)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-CLIENT-ID' => $this->x_client_id,
            'X-VERIFY' => $this->createXVerify($order -> sale_or_id)
        ])
            -> get("https://apps-uat.phonepe.com/v3/transaction/". $this->x_client_id ."/". $order -> sale_or_id ."/status")
            ->collect();
        return json_decode($response);
    }

    private function createXVerify($transaction)
    {
        $hash = hash('sha256', "/v3/transaction/" . $this->x_client_id . "/" . $transaction . "/status" . $this->salt_key);
        return $hash . "###" . $this->salt_index;
    }

}
