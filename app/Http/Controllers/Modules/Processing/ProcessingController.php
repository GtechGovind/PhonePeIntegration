<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\Processing;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Api\PhonePe\PhonePeStatusController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\Utility\OrderUtility;
use App\Models\RjtSlBooking;
use App\Models\SjtSlBooking;
use App\Models\SvSlBooking;
use App\Models\TpSlBooking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProcessingController extends Controller
{
    public function index($order_id)
    {
        return Inertia::render('Modules/Processing/Processing', [
            'order' => $order_id
        ]);
    }

    public function init($order_id)
    {
        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $order_id)
            ->first();

        $phonepe = new PhonePeStatusController();
        $response = $phonepe->getPaymentStatus($order);

        dd($response);

        OrderUtility::updateOrderStatus($response, $order);

        if ($response->success) {

            return ($order->op_type_id == env('ISSUE')
                ? ($order->product_id == env('PRODUCT_SJT') || $order->product_id == env('PRODUCT_RJT')
                    ? $this->genTicket($order)
                    : $this->genPass($order))
                : ($order->op_type_id == env('RELOAD')
                    ? $this->reloadPass($order)
                    : $this->graTransaction($order)));

        }

        return ErrorController::PhonePeError($response);

    }

    private function genTicket($order)
    {
        $api = new ApiController();
        $response = $api->genSjtRjtTicket($order);

        if (is_null($response)) return ErrorController::NullResponseError();
        if ($response->status == "BSE") return ErrorController::MmoplApiError($response);

        OrderUtility::updateSaleOrder($order, $response);

        if ($order->product_id == env('PRODUCT_SJT')) {
            foreach ($response->data->trips as $trip) {
                SjtSlBooking::store($response, $trip, $order);
            }
        } else {
            foreach ($response->data->trips as $trip) {
                RjtSlBooking::store($response, $trip, $order);
            }
        }

        return response([
            'status' => true,
            'product_id' => $order->product_id,
            'op_type_id' => $order->op_type_id,
        ]);
    }

    private function genPass($order)
    {
        $api = new ApiController();

        $response = $order->product_id == env('PRODUCT_SV')
            ? $api->genStoreValuePass($order)
            : $api->genTripPass($order);

        if ($response == null) return ErrorController::NullResponseError();
        if ($response->status == "BSE") return ErrorController::MmoplApiError($response);

        OrderUtility::updateSaleOrder($order, $response);

        return response([
            'status' => true,
            'product_id' => $order->product_id,
            'op_type_id' => $order->op_type_id,
        ]);

    }

    private function reloadPass($order)
    {
        $api = new ApiController();
        $response = $order->product_id == env('PRODUCT_SV')
            ? $api->reloadStoreValuePass($order)
            : $api->reloadTripPass($order);

        if ($response == null) return ErrorController::NullResponseError();
        if ($response->status == "BSE") return ErrorController::MmoplApiError($response);

        DB::table('sale_order')
            ->where('sale_or_no', '=', $order->sale_or_no)
            ->update([
                'mm_ms_acc_id' => $response->data->transactionId,
                'ms_qr_exp' => Carbon::createFromTimestamp($response->data->masterExpiry)->toDateTimeString(),
                'sale_or_status' => env('ORDER_RELOADED')
            ]);

        return response([
            'status' => true,
            'op_type_id' => $order->op_type_id,
            'product_id' => $order->product_id
        ]);

    }

    private function graTransaction($order)
    {
        $api = new ApiController();
        $statusResponse = $api->graInfo($order->ref_sl_qr, $order->des_stn_id);

        if ($statusResponse == null) return ErrorController::NullResponseError();
        if ($statusResponse->status == "BSE") return ErrorController::MmoplApiError($statusResponse);

        $response = $api->applyGra($statusResponse, $order);

        if ($response == null) return ErrorController::NullResponseError();
        if ($response->status == "BSE") return ErrorController::MmoplApiError($response);

        $old_order = DB::table('sale_order')
            ->where('ms_qr_no', '=', $order->ms_qr_no)
            ->first();

        if ($order->product_id == env('PRODUCT_SJT')) SjtSlBooking::store($response, $response->data->trips[0], $old_order);
        else if ($order->product_id == env('PRODUCT_RJT')) RjtSlBooking::store($response, $response->data->trips[0], $old_order);
        else if ($order->product_id == env('PRODUCT_SV')) SvSlBooking::store($old_order, $response);
        else TpSlBooking::store($old_order, $response);

        DB::table('sale_order')
            ->where('sale_or_no', '=', $order->sale_or_no)
            ->update([
                'mm_ms_acc_id' => $response->data->transactionId,
                'ms_qr_exp' => Carbon::createFromTimestamp($response->data->masterExpiry)->toDateTimeString(),
                'sale_or_status' => env('ORDER_GRA')
            ]);

        return response([
            'status' => true,
            'op_type_id' => $order->op_type_id,
            'product_id' => $order->product_id,
            'order_id' => $old_order->sale_or_no
        ]);

    }
}
