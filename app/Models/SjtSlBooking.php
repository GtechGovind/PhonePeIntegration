<?php /** @noinspection ALL */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SjtSlBooking extends Model
{
    use HasFactory;
    protected $table = 'sjt_sl_booking';
    public $timestamps = false;

    public static function store($response, $trip, $order)
    {
        $dir = env($trip->type ?? 'OUTWARD');

        if (is_null($dir)) $dir = env('OUTWARD');

        DB::table('sjt_sl_booking')->insert([
            'sale_or_id'    => $order -> sale_or_id,
            'mm_ms_acc_id'  => $response->data->transactionId,
            'mm_sl_acc_id'  => $trip->transactionId,
            'sl_qr_no'      => $trip->qrCodeId,
            'sl_qr_exp'     => Carbon::createFromTimestamp($trip->expiryTime)->toDateTimeString(),
            'ref_qr_no'     => $order->ref_sl_qr,
            'qr_dir'        => $dir,
            'qr_status'     => env($trip->tokenStatus),
            'qr_data'       => $trip->qrCodeData,
            'txn_date'      => Carbon::createFromTimestamp($response->data->travelDate)->toDateTimeString(),
        ]);
    }
}
