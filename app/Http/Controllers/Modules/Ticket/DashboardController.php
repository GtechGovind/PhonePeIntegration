<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Modules\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $upcomingOrders = $this->getUpcomingOrders();
        $recentOrders = $this->getRecentOrders();

        return Inertia::render('Modules/Ticket/Dashboard', [
            'user' => Auth::user(),
            'upcomingOrders' => $upcomingOrders,
            'recentOrders' => $recentOrders
        ]);
    }

    private function getUpcomingOrders()
    {
        return DB::table('sale_order as so')
            ->join('stations as s', 's.stn_id', 'so.src_stn_id')
            ->join('stations as d', 'd.stn_id', 'so.des_stn_id')
            ->where('so.pax_id', '=', Auth::id())
            ->where('so.sale_or_status', '=', env('ORDER_TICKET_GENERATED'))
            ->where('product_id', '=', env('PRODUCT_SJT'))
            ->orWhere('product_id', '=', env('PRODUCT_RJT'))
            ->select(['so.*', 's.stn_name as source', 'd.stn_name as destination'])
            ->get();

    }

    private function getRecentOrders()
    {
        return DB::table('sale_order as so')
            ->join('stations as s', 's.stn_id', 'so.src_stn_id')
            ->join('stations as d', 'd.stn_id', 'so.des_stn_id')
            ->where('so.pax_id', '=', Auth::id())
            ->where('so.sale_or_status', '=', env('ORDER_COMPLETED'))
            ->where('product_id', '=', env('PRODUCT_SJT'))
            ->orWhere('product_id', '=', env('PRODUCT_RJT'))
            ->select(['so.*', 's.stn_name as source', 'd.stn_name as destination'])
            ->limit(1)
            ->get();
    }
}
