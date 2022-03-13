<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\StoreValue;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Api\PhonePe\PhonePePaymentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\Utility;
use App\Http\Controllers\Modules\Utility\OrderUtility;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StoreValueReloadController extends Controller
{
    public function index($order_id)
    {
        return Inertia::render('Modules/StoreValue/Reload', [
            'order_id' => $order_id
        ]);
    }

    public function status($order_id)
    {
        $order = DB::table('sale_order as so')
            ->where('so.sale_or_status', '=', env('ORDER_TICKET_GENERATED'))
            ->where('so.sale_or_no', '=', $order_id)
            ->first();

        $api = new ApiController();
        $response = $api->reloadStoreValueStatus($order);

        if ($response->status == 'OK') {

            return response([
                'status' => true,
                'message' => 'Pass can be reloaded.'
            ]);

        }

        return response([
            'status' => false,
            'error' => $response->error
        ]);

    }

    public function reload(Request $request)
    {

        $old_order = DB::table('sale_order')
            ->where('sale_or_no', '=', $request->input('order_id'))
            ->first();

        $SaleOrderNumber = OrderUtility::genSaleOrderNumber($old_order->pass_id);
        SaleOrder::reload(
            $old_order,
            $request->input('reloadAmount'),
            $SaleOrderNumber,
        );

        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $SaleOrderNumber)
            ->first();

        $api = new PhonePePaymentController();
        $response = $api->pay($order);

        return $response->success
            ? response([
                'status' => true,
                'redirectUrl' => $response->data->redirectUrl,
                'order_id' => $SaleOrderNumber
            ])
            : response([
                'status' => false,
                'error' => $response,
                'order_id' => $SaleOrderNumber
            ]);

    }

}
