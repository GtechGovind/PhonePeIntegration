<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\Refund;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Api\PhonePe\PhonePeRefundController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\Utility\OrderUtility;
use Illuminate\Support\Facades\Auth;
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

        $processing_fee = $response->data->details->pass->processingFee;
        $processing_fee_amount = $response->data->details->pass->processingFeeAmount;
        $refund_amount = $response->data->details->pass->refundAmount;
        $pass_price = $response->data->details->pass->passPrice;

        dd($response);

        if ($order->product_id == env('PRODUCT_SV'))
        {
            $balance = $response->data->details->pass->remainingBalance;

            $lastOrder = DB::table('sale_order')
                ->where('ms_qr_no', '=', $order->ms_qr_no)
                ->orderBy('txn_date', 'desc')
                ->first();

            if (!is_null($lastOrder))
            {
                if ($balance > $lastOrder->total_price)
                {
                    return response([
                        'status' => false,
                        'error' => 'Please spend ₹ ' . ($balance - $lastOrder->total_price) . ' to refund pass !'
                    ]);
                }
            }
        }

        return response([
            'status' => true,
            'refund' => [
                'order_id' => $order_id,
                'processing_fee' => $processing_fee,
                'processing_fee_amount' => $processing_fee_amount,
                'refund_amount' => $refund_amount,
                'pass_price' => $pass_price
            ]
        ]);
    }

    public function apply($order_id)
    {
        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $order_id)
            ->first();

        $api = new ApiController();
        $response = $api->getRefundInfo($order);

        if ($response == null) return response([
            'status' => false,
            'error' => 'Please check your internet connection !'
        ]);

        if ($response->status == "BSE") return response([
            'status' => false,
            'error' => $response->error
        ]);

        if ($order->product_id == env('PRODUCT_SJT') || $order->product_id == env('PRODUCT_RJT')) return $this->sjtRjtRefund($order, $response);
        else if ($order->product_id == env('PRODUCT_SV')) return $this->svRefund($order, $response);
        else if ($order->product_id == env('PRODUCT_TP')) return $this->tpRefund($order, $response);

        return response([
            'status' => false,
            'error' => 'Invalid product'
        ]);

    }

    private function sjtRjtRefund($order, $response)
    {

        // GENERATING REFUND ORDER
        $refund_order_id = OrderUtility::genSaleOrderNumber($order->pass_id);

        // CREATING REFUND ORDER
        DB::table('refund_order')
            ->insert([
                'ref_or_no' => $refund_order_id,
                'sale_or_id' => $order->sale_or_id,
                'pax_id' => Auth::id(),
                'unit' => $order->unit,
                'ref_amt' => $response->data->details->pass->refundAmount,
                'ref_chr' => $response->data->details->pass->processingFee,
                'ref_or_status' => env('ISSUE'),
                'txn_date' => now()
            ]);

        // REFUNDING PHONEPE
        $phonepe = new PhonePeRefundController();
        $refundResponse = $phonepe->init($order, $refund_order_id, $response->data->details->pass->refundAmount);

        // IF PHONEPE REFUND FAILED
        if (!$refundResponse->success) {
            return response([
                'status' => false,
                'error' => 'failed to refund order, try again or contact phonepe!'
            ]);
        }

        // UPDATING REFUND ORDER
        DB::table('refund_order')
            ->where('sale_or_id', '=', $order->sale_or_id)
            ->update([
                'pg_txn_no' => $refundResponse->data->providerReferenceId
            ]);

        // REFUNDING MMOPL
        $api = new ApiController();
        $refundApiResponse = $api->refundTicket($response, $refund_order_id);

        // MMOPL ERRORS
        if ($refundApiResponse == null) {
            return response([
                'status' => false,
                'error' => 'Please check your internet connection !'
            ]);
        }

        if ($refundApiResponse->status == "BSE") {
            return response([
                'status' => false,
                'error' => $response->error
            ]);
        }

        // UPDATING OLD ORDER
        DB::table('sale_order')
            ->where('sale_or_no', '=', $order->sale_or_no)
            ->update([
                'sale_or_status' => env('ORDER_REFUNDED')
            ]);

        return response([
            'status' => true,
            'message' => 'Order refunded Successfully.'
        ]);

    }

    private function svRefund($order, $response)
    {

        $lastOrder = DB::table('sale_order')
            ->where('ms_qr_no', '=', $order->ms_qr_no)
            ->orderBy('txn_date', 'desc')
            ->first();

        // GENERATING REFUND ORDER
        $refund_order_id = OrderUtility::genSaleOrderNumber($lastOrder->pass_id);

        // CREATING REFUND ORDER
        DB::table('refund_order')
            ->insert([
                'ref_or_no' => $refund_order_id,
                'sale_or_id' => $lastOrder->sale_or_id,
                'pax_id' => Auth::id(),
                'unit' => $lastOrder->unit,
                'ref_amt' => $response->data->details->pass->refundAmount,
                'ref_chr' => $response->data->details->pass->processingFee,
                'ref_or_status' => env('ISSUE'),
                'txn_date' => now()
            ]);

        // REFUNDING PHONEPE
        $phonepe = new PhonePeRefundController();
        $refundResponse = $phonepe->init($lastOrder, $refund_order_id, $response->data->details->pass->refundAmount);

        // IF PHONEPE REFUND FAILED
        if (!$refundResponse->success) {
            return response([
                'status' => false,
                'error' => 'failed to refund order, try again or contact phonepe!'
            ]);
        }

        // UPDATING REFUND ORDER
        DB::table('refund_order')
            ->where('sale_or_id', '=', $lastOrder->sale_or_id)
            ->update([
                'pg_txn_no' => $refundResponse->data->providerReferenceId
            ]);

        // REFUNDING MMOPL
        $api = new ApiController();
        $refundApiResponse = $api->refundTicket($response, $refund_order_id);

        // MMOPL ERRORS
        if ($refundApiResponse == null) {
            return response([
                'status' => false,
                'error' => 'Please check your internet connection !'
            ]);
        }

        if ($refundApiResponse->status == "BSE") {
            return response([
                'status' => false,
                'error' => $response->error
            ]);
        }

        // UPDATING OLD ORDER
        DB::table('sale_order')
            ->where('sale_or_no', '=', $lastOrder->sale_or_no)
            ->update([
                'sale_or_status' => env('ORDER_REFUNDED')
            ]);

        // UPDATE ORIGINAL ORDER
        DB::table('sale_order')
            ->where('sale_or_no', '=', $order->sale_or_no)
            ->update([
                'sale_or_status' => env('ORDER_REFUNDED')
            ]);

        return response([
            'status' => true,
            'message' => 'Order refunded Successfully.'
        ]);

    }

    private function tpRefund($order, $response)
    {
        // GENERATING REFUND ORDER
        $refund_order_id = OrderUtility::genSaleOrderNumber($order->pass_id);

        // CREATING REFUND ORDER
        DB::table('refund_order')
            ->insert([
                'ref_or_no' => $refund_order_id,
                'sale_or_id' => $order->sale_or_id,
                'pax_id' => Auth::id(),
                'unit' => $order->unit,
                'ref_amt' => $response->data->details->pass->refundAmount,
                'ref_chr' => $response->data->details->pass->processingFee,
                'ref_or_status' => env('ISSUE'),
                'txn_date' => now()
            ]);


        // REFUNDING MMOPL
        $api = new ApiController();
        $refundApiResponse = $api->refundTicket($response, $refund_order_id);

        // MMOPL ERRORS
        if ($refundApiResponse == null) {
            return response([
                'status' => false,
                'error' => 'Please check your internet connection !'
            ]);
        }

        if ($refundApiResponse->status == "BSE") {
            return response([
                'status' => false,
                'error' => $response->error
            ]);
        }

        // UPDATING OLD ORDER
        DB::table('sale_order')
            ->where('sale_or_no', '=', $order->sale_or_no)
            ->update([
                'sale_or_status' => env('ORDER_REFUNDED')
            ]);

        return response([
            'status' => true,
            'message' => 'Order refunded Successfully.'
        ]);

    }


}
