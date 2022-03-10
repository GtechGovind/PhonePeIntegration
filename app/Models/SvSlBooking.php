<?php /** @noinspection ALL */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SvSlBooking extends Model
{
    use HasFactory;

    protected $table = 'sv_sl_booking';
    public $timestamps = false;

    public static function store($order, $response)
    {
        DB::table('sv_sl_booking')
            ->insert([
                'sale_or_id'    => $order->sale_or_id,
                'txn_date'      => Carbon::createFromTimestamp($response->data->travelDate)->toDateTimeString(),
                'mm_ms_acc_id'  => $order->mm_ms_acc_id,
                'mm_sl_acc_id'  => $response->data->trips[0]->transactionId,
                'sl_qr_no'      => $response->data->trips[0]->qrCodeId,
                'sl_qr_exp'     => Carbon::createFromTimestamp($response->data->trips[0]->expiryTime)->toDateTimeString(),
                'amt_deducted'  => $response->data->amount,
                'balance_amt'   => $response->data->balance,
                'qr_status'     => env($response->data->trips[0]->tokenStatus),
                'qr_data'       => $response->data->trips[0]->qrCodeData,
            ]);
    }
}
