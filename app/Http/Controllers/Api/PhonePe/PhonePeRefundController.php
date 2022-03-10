<?php

namespace App\Http\Controllers\Api\PhonePe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhonePeRefundController extends Controller
{

    public $salt_key;
    public $app_url;
    public $salt_index;
    public $x_client_id;

    public function __construct()
    {
        $this->salt_key = env('PHONEPAY_SLAT_KEY');
        $this->salt_index = env("PHONEPAY_SLAT_INDEX");
        $this->x_client_id = env("PHONEPAY_CLIENT_ID");
        $this->app_url = env('APP_URL');
    }

    public function init($order, $refund_order_no, $refundAmount)
    {

        $payload = $this->createPayload($order, $refund_order_no, $refundAmount);
        $request = $this->createRequest($payload);
        $x_verify = $this->createXVerify($payload);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-CLIENT-ID' => $this->x_client_id,
            'X-VERIFY' => $x_verify
        ])
            ->withBody($request, 'application/json')
            ->post('https://apps-uat.phonepe.com/v3/credit/backToSource')->collect();

        return json_decode($response);

    }

    private function createRequest($payload)
    {
        return '{"request": "' . base64_encode($payload) . '"}';
    }

    private function createPayload($order, $refund_order_no, $refundAmount)
    {

        $payload = new RefundPayload();
        $payload->merchantId = $this->x_client_id;
        $payload->transactionId = $refund_order_no;
        $payload->providerReferenceId = $order->pg_txn_no;
        $payload->amount = $refundAmount * 100;
        $payload->merchantOrderId = $order->sale_or_no;
        $payload->message = "refund for cancelled order";

        return json_encode($payload, JSON_FORCE_OBJECT);

    }

    private function createXVerify($payload)
    {
        $hash = hash('sha256', base64_encode($payload) . "/v3/credit/backToSource" . $this->salt_key);
        return $hash . "###" . $this->salt_index;
    }

}
