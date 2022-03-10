<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\Utility;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderUtility extends Controller
{
    public static function updateOrderStatus($response, $order)
    {
        if ($response->success && $order -> sale_or_status == env('ORDER_GENERATED'))
        {
            DB::table('sale_order')
                ->where('sale_or_no', '=', $order->sale_or_no)
                ->update([
                    'pg_txn_no' => $response->data->providerReferenceId,
                    'sale_or_status' => env('ORDER_PAYMENT_SUCCESS')
                ]);
        }
        else if ($order -> sale_or_status == env('ORDER_GENERATED'))
        {
            DB::table('sale_order')
                ->where('sale_or_no', '=', $order->sale_or_no)
                ->where('sale_or_status', '=', env('ORDER_GENERATED'))
                ->update([
                    'sale_or_status' => env('ORDER_PAYMENT_FAILED')
                ]);
        }
    }

    public function updateSaleOrder($order, $response)
    {
        DB::table('sale_order')
            ->where('sale_or_no', '=', $order->sale_or_no)
            ->update([
                'ms_qr_no' => $response->data->masterTxnId,
                'mm_ms_acc_id' => $response->data->transactionId,
                'ms_qr_exp' => Carbon::createFromTimestamp($response->data->masterExpiry)->toDateTimeString(),
                'sale_or_status' => env('ORDER_TICKET_GENERATED')
            ]);
    }

    public static function genSaleOrderNumber($pass_id)
    {
        return "ATEK" . $pass_id . strtoupper(dechex(Auth::user()->pax_mobile + time()));
    }
}
