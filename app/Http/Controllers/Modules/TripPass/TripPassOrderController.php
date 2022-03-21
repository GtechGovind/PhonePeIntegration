<?php

namespace App\Http\Controllers\Modules\TripPass;

use App\Http\Controllers\Api\MMOPL\ApiController;
use App\Http\Controllers\Api\PhonePe\PhonePePaymentController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Modules\Utility\OrderUtility;
use App\Models\SaleOrder;
use App\Models\TpSlBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TripPassOrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Modules/TripPass/Order', [
            'stations' => DB::table('stations')->get(['stn_id', 'stn_name']),
            'trips' => 45,
            'validity' => 30
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'source_id' => 'required|integer|min:1|max:12',
            'destination_id' => 'required|integer|min:1|max:12',
            'fare' => 'required',
        ]);

        $saleOrderNumber = OrderUtility::genSaleOrderNumber(
            env('PASS_TP')
        );

        $saleOrder = new SaleOrder();
        $saleOrder->storeTp($request, $saleOrderNumber);

        $order = DB::table('sale_order as so')
            ->join('stations as s', 's.stn_id', '=', 'so.src_stn_id')
            ->join('stations as d', 'd.stn_id', '=', 'so.des_stn_id')
            ->where('sale_or_no', '=', $saleOrderNumber)
            ->select(['so.*', 's.stn_name as source_name', 'd.stn_name as destination_name'])
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

    public function issueTrip($order_id): RedirectResponse
    {
        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $order_id)
            ->first();

        $api = new ApiController();
        $response = $api->genTrip($order);

        if (!is_null($response)) {

            if ($response->status == "OK") {

                $tpSl = new TpSlBooking();
                $tpSl->store($order, $response);

            }

        }

        return redirect()->route('tp.dashboard');

    }

    public function canIssuePass()
    {
        $api = new ApiController();
        $response = $api -> canIssuePassTP(
            env('PRODUCT_TP'),
            env('PASS_TP')
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
                'error' => $response -> error
            ]);
        }

    }

}
