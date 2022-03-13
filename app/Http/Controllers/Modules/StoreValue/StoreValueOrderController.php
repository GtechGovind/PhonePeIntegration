<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\StoreValue;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Api\PhonePe\PhonePePaymentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\Utility;
use App\Http\Controllers\Modules\Utility\OrderUtility;
use App\Models\SaleOrder;
use App\Models\SvSlBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StoreValueOrderController extends Controller
{
    public function index()
    {
        return Inertia::render('Modules/StoreValue/Order');
    }

    public function create(Request $request)
    {
        $request->validate([
            'price' => 'required|integer|min:100|max:3000|multiple_of:100',
        ]);

        $saleOrderNumber = OrderUtility::genSaleOrderNumber($request->input('pass_id'));
        SaleOrder::storeSv($request, $saleOrderNumber);

        $saleOrder = new SaleOrder();
        $saleOrder->store($request, $saleOrderNumber);

        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $saleOrderNumber)
            ->first();

        $api = new PhonePePaymentController();
        $response = $api->pay($order);

        return $response->success
            ? response([
                'status' => true,
                'redirectUrl' => $response->data->redirectUrl,
                'order_id' => $saleOrderNumber
            ])
            : response([
                'status' => false,
                'error' => $response,
                'order_id' => $saleOrderNumber
            ]);

    }

    public function issueTrip($order_id)
    {
        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $order_id)
            ->first();

        $api = new ApiController();
        $response = $api->genTrip($order);

        if ($response->status == "OK") SvSlBooking::store($order, $response);

        return redirect()->route('sv.dashboard');
    }

    public function canIssuePass()
    {
        $api = new ApiController();
        $response = $api -> canIssuePass(
            env('PRODUCT_SV'),
            env('PASS_SV')
        );

        if ($response -> status == 'OK')
        {
            return response([
                'status' => true,
                'message' => 'Pass can be issued !'
            ]);
        }
        else
        {
            return response([
                'status' => false,
                'error' => $response
            ]);
        }

    }

}
