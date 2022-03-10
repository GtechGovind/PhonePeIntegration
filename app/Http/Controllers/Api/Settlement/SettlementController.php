<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Api\Settlement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use function env;

class SettlementController extends Controller
{

    public $base_url;

    public function __construct()
    {
        $this->base_url = env('BASE_URL');
    }

    public function getIssueUnsettledData(Request $request)
    {
        $request -> validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'pg_id' => 'required',
            'operator_id' => 'required',
        ]);

        return Http::get($this->base_url . "/atek_backend/v1/getIssueRecords.php", [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'pg_id' => $request->input('pg_id'),
            'operator_id' => $request->input('operator_id'),
        ])->collect();
    }

    public function getRefundUnsettledData(Request $request)
    {

        $request -> validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'pg_id' => 'required',
            'operator_id' => 'required',
        ]);

        return Http::get($this->base_url . "/atek_backend/v1/getRefundRecords.php", [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'pg_id' => $request->input('pg_id'),
            'operator_id' => $request->input('operator_id'),
        ])->collect();
    }

    public function setIssueUnsettledData(Request $request)
    {
        return Http::withBody($request->getContent(), 'application/json')
            ->post($this->base_url . "/atek_backend/v1/setIssueRecords.php")
            ->collect();
    }

    public function setRefundUnsettledData(Request $request)
    {
        return Http::withBody($request->getContent(), 'application/json')
            ->post($this->base_url . "/atek_backend/v1/setRefundRecords.php")
            ->collect();
    }

}
