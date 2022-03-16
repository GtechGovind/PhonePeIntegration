<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderDetailsController extends Controller
{
    public function index($order_id)
    {
        $order = DB::table('sale_order')
            ->where('sale_or_no', '=', $order_id)
            ->first();

        return Inertia::render('Modules/OrderDetail', [
            'order' => $order
        ]);

    }
}
