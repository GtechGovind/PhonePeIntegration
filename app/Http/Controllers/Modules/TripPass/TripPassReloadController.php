<?php

namespace App\Http\Controllers\Modules\TripPass;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\Utility;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TripPassReloadController extends Controller
{
    public function index($order_id)
    {
        $order = DB::table('sale_order as so')
            ->join('stations as s', 's.stn_id', '=', 'so.src_stn_id')
            ->join('stations as d', 'd.stn_id', '=', 'so.des_stn_id')
            ->where('sale_or_no', '=', $order_id)
            ->select(['so.*', 's.stn_name as source', 'd.stn_name as destination'])
            ->orderBy('so.sale_or_id', 'desc')
            ->first();

        $fare = DB::table('fares')
            ->where('source', '=', $order -> src_stn_id)
            ->where('destination', '=', $order -> des_stn_id)
            ->where('fare_table_id', '=', 2)
            ->first();

        return Inertia::render('Modules/TripPass/Reload', [
            'order_id'      => $order_id,
            'fare'          => $fare -> fare,
            'source'        => $order -> source,
            'destination'   => $order -> destination,
            'trips'         => 45,
            'validity'      => 30
        ]);
    }

    public function status($order_id)
    {
        $order = DB::table('sale_order as so')
            ->where('so.sale_or_status', '=', env('ORDER_TICKET_GENERATED'))
            ->where('so.sale_or_no', '=', $order_id)
            ->first();

        $api = new ApiController();
        $response = $api->reloadTripPassStatus($order);

        return $response->status == 'OK' ? response([
            'status' => true,
            'message' => 'Pass can be reloaded.'
        ]) : response([
            'status' => false,
            'error' => $response->error
        ]);

    }

    public function reload(Request $request)
    {
        $old_order = DB::table('sale_order')
            ->where('sale_or_no', '=', $request->input('order_id'))
            ->first();

        $SaleOrderNumber = Utility::genSaleOrderNumber($old_order->pass_id);
        SaleOrder::reload(
            $old_order,
            $request->input('reloadAmount'),
            $SaleOrderNumber
        );

        return redirect()->to('/payment/' . $SaleOrderNumber);
    }

}
