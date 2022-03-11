<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\Ticket;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketStatusController extends Controller
{
    public function index()
    {
        $orders = DB::table('sale_order')
            ->where('pax_id', '=', Auth::id())
            ->where('op_type_id', '=', env('ISSUE'))
            ->where('product_id', '=', env('PRODUCT_SJT'))
            ->orWhere('product_id', '=', env('PRODUCT_RJT'))
            ->where('sale_or_status', '=', env('ORDER_TICKET_GENERATED'))
            ->get();

        if (!is_null($orders))
        {
            foreach ($orders as $order) {

                $count = 0;

                if ($order->product_id == env('PRODUCT_SJT'))
                {
                    $slaves = DB::table('sjt_sl_booking')
                        ->where('sale_or_id', '=', $order -> sale_or_id)
                        ->get();
                }
                else
                {
                    $slaves = DB::table('rjt_sl_booking')
                        ->where('sale_or_id', '=', $order -> sale_or_id)
                        ->get();
                }

                foreach ($slaves as $slave)
                {
                    $api = new ApiController();
                    $response = $api -> getSlaveStatus($slave -> sl_qr_no);

                    if ($response -> status = 'OK')
                    {
                        $status = $response -> data -> trips[0] -> tokenStatus;

                        if ($status == 'COMPLETED' || $status == 'EXPIRED') {

                            $count++;

                            if ($count == count($slaves)) {

                                DB::table('sale_order')
                                    -> where('sale_or_no', '=', $order -> sale_or_no)
                                    -> update([
                                        'sale_or_status' => env('ORDER_COMPLETED')
                                    ]);

                            }

                        }

                        if ($order->product_id == env('PRODUCT_SJT')) {

                            DB::table('sjt_sl_booking')
                                ->where('sl_qr_no', '=', $slave -> sl_qr_no)
                                ->update([
                                    'qr_status' => env($status)
                                ]);

                        } else {

                            DB::table('rjt_sl_booking')
                                ->where('sl_qr_no', '=', $slave -> sl_qr_no)
                                ->update([
                                    'qr_status' => env($status)
                                ]);

                        }

                    }
                }

            }

            return response([
                'status' => true,
                'message' => 'Tickets updated successfully.'
            ]);

        }

        return response([
            'status' => false,
            'error' => 'No orders found !'
        ]);

    }
}
