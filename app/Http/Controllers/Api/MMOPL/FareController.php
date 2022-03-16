<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Api\MMOPL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FareController extends Controller
{
    public function getFare(Request $request)
    {
        $request->validate([
            'source' => 'required',
            'destination' => 'required',
            'pass_id' => 'required'
        ]);

        if ($request -> input('pass_id') == env('PASS_SJT')) $fare_table_id = 0;
        else if ($request -> input('pass_id') == env('PASS_SJT')) $fare_table_id = 1;
        else if ($request -> input('pass_id') == env('PASS_SV')) $fare_table_id = 3;
        else if ($request -> input('pass_id') == env('PASS_TP')) $fare_table_id = 2;

        $fare = DB::table('fares')
            -> where('source', '=', $request -> input('source'))
            -> where('destination', '=', $request -> input('destination'))
            -> where('fare_table_id', '=', $fare_table_id)
            -> first();

        return response([
            'status' => true,
            'fare' => $fare -> fare
        ]);

    }
}
