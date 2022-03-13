<?php

namespace App\Http\Controllers\Modules\Refund;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function info($order_id)
    {
        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $order_id)
            ->first();

        $api = new ApiController();
        $response = $api->getRefundInfo($order);

        if ($response == null) return response([
            'status' => false,
            'error' => 'Please check your internet connection!'
        ]);

        if ($response->status == "BSE") return response([
            'status' => false,
            'error' => $response->error
        ]);

        return response([
            'status' => true,
            'refund' => [
                'order_id' => $order_id,
                'processing_fee' => $response->data->details->pass->processingFee,
                'processing_fee_amount' => $response->data->details->pass->processingFeeAmount,
                'refund_amount' => $response->data->details->pass->refundAmount,
                'pass_price' => $response->data->details->pass->passPrice
            ]
        ]);
    }

    public function apply()
    {

    }

}
