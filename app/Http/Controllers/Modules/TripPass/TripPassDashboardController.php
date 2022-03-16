<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\TripPass;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TripPassDashboardController extends Controller
{
    public function index()
    {
        $pass = DB::table('sale_order as so')
            ->join('stations as s', 's.stn_id', 'so.src_stn_id')
            ->join('stations as d', 'd.stn_id', 'so.des_stn_id')
            ->where('so.pax_id', '=', Auth::id())
            ->where('so.sale_or_status', '=', env('ORDER_TICKET_GENERATED'))
            ->where('so.product_id', '=', env('PRODUCT_TP'))
            ->orderBy('so.txn_date', 'desc')
            ->select(['so.*', 's.stn_name as source', 'd.stn_name as destination'])
            ->first();

        if (is_null($pass)) return redirect()->route('tp.order');

        $trip = DB::table('tp_sl_booking')
            ->where('sale_or_id', '=', $pass->sale_or_id)
            ->where('qr_status', '!=', env('COMPLETED'))
            ->where('qr_status', '!=', env('EXPIRED'))
            ->orderBy('txn_date', 'desc')
            ->first();

        return Inertia::render('Modules/TripPass/Dashboard', [
            'user' => Auth::user(),
            'pass' => $pass,
            'trip' => $trip,
            'stations' => DB::table('stations')->get(['stn_id', 'stn_name'])
        ]);

    }
}
