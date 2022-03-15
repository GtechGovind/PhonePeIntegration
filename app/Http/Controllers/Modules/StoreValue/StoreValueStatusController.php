<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\StoreValue;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StoreValueStatusController extends Controller
{
    public function index($master_id)
    {
        $pass = DB::table('sale_order as so')
            ->where('so.product_id', '=', env('PRODUCT_SV'))
            ->where('so.op_type_id', '=', env('ISSUE'))
            ->where('so.ms_qr_no', '=', $master_id)
            ->where('so.sale_or_status', '=', env('ORDER_TICKET_GENERATED'))
            ->first();

        if (is_null($pass)) return response([
            'status' => false,
            'error' => 'No order found !'
        ]);

        $api = new ApiController();
        $response = $api->getSlaveStatus($pass->ms_qr_no);

        if ($response->status == "OK") {

            if (count($response -> data -> trips) > 0) {

                DB::table('sv_sl_booking')
                    ->where('mm_ms_acc_id', '=', $pass->mm_ms_acc_id)
                    ->where('sl_qr_no', '=', $response->data->trips[0]->qrCodeId)
                    ->update([
                        'qr_status' => env($response->data->trips[0]->tokenStatus),
                        'sl_qr_exp' => Carbon::createFromTimestamp($response->data->trips[0]->expiryTime),
                        'qr_data' => $response->data->trips[0]->qrCodeData
                    ]);

            }

            return response([
                'status' => true,
                'data' => $response -> data
            ]);

        }
        else
        {
            return response([
                'status' => false,
                'message' => 'unable to fetch data from mmopl!'
            ]);
        }
    }
}
