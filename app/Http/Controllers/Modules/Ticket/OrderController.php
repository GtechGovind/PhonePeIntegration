<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\Ticket;

use App\Http\Controllers\Api\PhonePe\PhonePePaymentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\Utility\OrderUtility;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function index()
    {
        return Inertia::render('Modules/Ticket/Order', [
            'stations' => DB::table('stations')->get(['stn_id', 'stn_name'])
        ]);
    }

    /*
        CHECK IF USER HAS
        ANY UNPAID ORDERS
    */
    public function isPending()
    {

        $pendingOrders = DB::table('sale_order')
            ->where('pax_id', Auth::id())
            ->where('sale_or_status', '=', env('ORDER_GENERATED'))
            ->get()->count();

        return $pendingOrders > 0
            ? response(['isPendingPayment' => true])
            : response(['isPendingPayment' => false]);

    }

    /*
        CREATE NEW ORDER
    */
    public function create(Request $request)
    {
        $request->validate([
            'source_id' => ['required'],
            'destination_id' => ['required'],
            'pass_id' => ['required'],
            'quantity' => ['required'],
            'fare' => ['required']
        ]);

        $saleOrderNumber = OrderUtility::genSaleOrderNumber(
            $request->input('pass_id')
        );

        $saleOrder = new SaleOrder();
        $saleOrder->store($request, $saleOrderNumber);

        $order = DB::table('sale_order as so')
            ->join('stations as s', 's.stn_id', '=', 'so.src_stn_id')
            ->join('stations as d', 'd.stn_id', '=', 'so.des_stn_id')
            ->where('sale_or_no', '=', $saleOrderNumber)
            ->select(['so.*', 's.stn_name as source_name', 'd.stn_name as destination_name'])
            ->first();

        dd($order);

        $api = new PhonePePaymentController();
        $response = $api->pay($order);

        return $response->success
            ? response([
                'status' => true,
                'redirectUrl' => $response->data->redirectUrl,
                'order_id' => $order->salr_or_id
            ])
            : response([
                'status' => false,
                'order_id' => $order->salr_or_id
            ]);
    }
}
