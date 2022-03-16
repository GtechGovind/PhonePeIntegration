<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleOrder extends Model
{
    use HasFactory;
    protected $table = 'sale_order';
    public $timestamps = false;

    public static function store(Request $request, $saleOrderNumber)
    {
        DB::table('sale_order')->insert([
            'sale_or_no'        => $saleOrderNumber,
            'txn_date'          => Carbon::now(),
            'pax_id'            => Auth::id(),
            'src_stn_id'        => $request -> input('source_id'),
            'des_stn_id'        => $request -> input('destination_id'),
            'unit'              => $request -> input('quantity'),
            'unit_price'        => $request -> input('fare'),
            'total_price'       => $request -> input('quantity') * $request -> input('fare'),
            'media_type_id'     => env('MEDIA_TYPE_ID_MOBILE'),
            'product_id'        => $request->input('pass_id') === env('PASS_SJT') ? env('PRODUCT_SJT') :  env('PRODUCT_RJT'),
            'op_type_id'        => env('ISSUE'),
            'pass_id'           => $request->input('pass_id'),
            'pg_id'             => env('PHONE_PE_PG'),
            'sale_or_status'    => env('ORDER_GENERATED')
        ]);
    }

    public static function storeSv(Request $request, $saleOrderNumber)
    {
        DB::table('sale_order')->insert([
            'sale_or_no'        => $saleOrderNumber,
            'txn_date'          => Carbon::now(),
            'pax_id'            => Auth::id(),
            'unit'              => 1,
            'unit_price'        => $request -> input('price'),
            'total_price'       => $request -> input('price'),
            'media_type_id'     => env('MEDIA_TYPE_ID_MOBILE'),
            'product_id'        => env('PRODUCT_SV'),
            'op_type_id'        => env('ISSUE'),
            'pass_id'           => env('PASS_SV'),
            'pg_id'            => env('PHONE_PE_PG'),
            'sale_or_status'    => env('ORDER_GENERATED')
        ]);
    }

    public static function reload($old_order, $reload_amount, $saleOrderNumber)
    {
        DB::table('sale_order')->insert([
            'sale_or_no'        => $saleOrderNumber,
            'txn_date'          => Carbon::now(),
            'pax_id'            => Auth::id(),
            'src_stn_id'        => $old_order -> src_stn_id ?? null,
            'des_stn_id'        => $old_order -> des_stn_id ?? null,
            'ms_qr_no'          => $old_order -> ms_qr_no,
            'unit'              => 1,
            'unit_price'        => $reload_amount,
            'total_price'       => $reload_amount,
            'media_type_id'     => env('MEDIA_TYPE_ID_MOBILE'),
            'product_id'        => $old_order->product_id,
            'op_type_id'        => env('RELOAD'),
            'pass_id'           => $old_order->pass_id,
            'pg_id'            => env('PHONE_PE_PG'),
            'sale_or_status'    => env('ORDER_GENERATED')
        ]);
    }

    public static function storeTp(Request $request, $saleOrderNumber)
    {
        DB::table('sale_order')->insert([
            'sale_or_no'        => $saleOrderNumber,
            'txn_date'          => Carbon::now(),
            'pax_id'            => Auth::id(),
            'src_stn_id'        => $request -> input('source_id'),
            'des_stn_id'        => $request -> input('destination_id'),
            'unit'              => 1,
            'unit_price'        => $request -> input('fare'),
            'total_price'       => $request -> input('fare'),
            'media_type_id'     => env('MEDIA_TYPE_ID_MOBILE'),
            'product_id'        => env('PRODUCT_TP'),
            'pass_id'           => env('PASS_TP'),
            'op_type_id'        => env('ISSUE'),
            'pg_id'            => env('PHONE_PE_PG'),
            'sale_or_status'    => env('ORDER_GENERATED')
        ]);
    }
}
