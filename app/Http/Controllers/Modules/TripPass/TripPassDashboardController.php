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
        $pass = DB::table('sale_order')
            ->where('pax_id', '=', Auth::id())
            ->where('sale_or_status', '=', env('ORDER_TICKET_GENERATED'))
            ->where('product_id', '=', env('PRODUCT_TP'))
            ->orderBy('txn_date', 'desc')
            ->first();

        if (is_null($pass)) return redirect()->route('tp.order');

        $trip = DB::table('tp_sl_booking')
            ->where('sale_or_id', '=', $pass->sale_or_id)
            ->where('qr_status', '!=', env('COMPLETED'))
            ->where('qr_status', '!=', env('EXPIRED'))
            ->orderBy('txn_date', 'desc')
            ->first();

        return Inertia::render('TripPass/Dashboard', [
            'user' => Auth::user(),
            'pass' => $pass,
            'trip' => $trip
        ]);

    }
}
