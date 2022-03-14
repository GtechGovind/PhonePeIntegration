<?php /** @noinspection LaravelFunctionsInspection */

namespace App\Http\Controllers\Api\MMOPL;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public $op_type_id_issue;
    public $op_type_id_reload;
    public $reg_fee_sv;
    public $base_url;
    public $pg_id;
    public $operator_id;
    public $auth;
    public $media_type_id;

    public function __construct()
    {
        $this->op_type_id_issue = env('ISSUE');
        $this->op_type_id_reload = env('RELOAD');
        $this->pg_id = env('PHONE_PE_PG');
        $this->operator_id = env('OPERATOR_ID');
        $this->base_url = env("BASE_URL_MMOPL");
        $this->auth = env("API_SECRET");
        $this->reg_fee_sv = env('SV_REG_FEE');
        $this->media_type_id = env('MEDIA_TYPE_ID_MOBILE');
    }

    public function genSjtRjtTicket($data)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                "data": {
                    "fare"                      : "' . $data->total_price . '",
                    "source"                    : "' . $data->src_stn_id . '",
                    "destination"               : "' . $data->des_stn_id . '",
                    "tokenType"                 : "' . $data->pass_id . '",
                    "supportType"               : "' . $data->media_type_id . '",
                    "qrType"                    : "' . $data->product_id . '",
                    "operationTypeId"           : "' . $this->op_type_id_issue . '",
                    "operatorId"                : "' . $this->operator_id . '",
                    "operatorTransactionId"     : "' . $data->sale_or_no . '",
                    "name"                      : "' . Auth::user()->pax_name . '",
                    "email"                     : "' . Auth::user()->pax_email . '",
                    "mobile"                    : "' . Auth::user()->pax_mobile . '",
                    "activationTime"            : "' . $data->insert_date . '",
                    "trips"                     : "' . $data->unit . '"
                },
                "payment": {
                    "pass_price"                : "' . $data->total_price . '",
                    "pgId"                      : "' . $this->pg_id . '",
                    "pg_order_id"               : "' . $data->pg_txn_no . '"
                }
            }', 'application/json')
            ->post($this->base_url . '/qrcode/issueToken')
            ->collect();
        return json_decode($response);
    }

    public function getSlaveStatus($slave)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->get($this->base_url . '/qrcode/status/' . $slave)
            ->collect();

        return json_decode($response);
    }

    public function genStoreValuePass($data)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                "data": {
                    "fare"                      : "' . $data->total_price . '",
                    "tokenType"                 : "' . $data->pass_id . '",
                    "supportType"               : "' . $data->media_type_id . '",
                    "registrationFee"           : "' . $this->reg_fee_sv . '",
                    "qrType"                    : "' . $data->product_id . '",
                    "operationTypeId"           : "' . $this->op_type_id_issue . '",
                    "operatorId"                : "' . $this->operator_id . '",
                    "name"                      : "' . Auth::user()->pax_name . '",
                    "email"                     : "' . Auth::user()->pax_email . '",
                    "mobile"                    : "' . Auth::user()->pax_mobile . '",
                    "activationTime"            : "' . Carbon::now() . '",
                    "operatorTransactionId"     : "' . $data->sale_or_no . '"
                },
                "payment": {
                    "pass_price"                : "' . $data->total_price . '",
                    "pgId"                      : "' . $this->pg_id . '",
                    "pg_order_id"               : "' . $data->pg_txn_no . '"
                }
            }', 'application/json')
            ->post($this->base_url . '/qrcode/issuePass')
            ->collect();

        return json_decode($response);

    }

    public function getPassStatus($master)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->get($this->base_url . '/pass/bookings?masterTxnId=' . $master)
            ->collect();

        return json_decode($response);
    }

    public function genTrip($data)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
             "data": {
                    "tokenType"             :   "' . $data->total_price . '",
                    "operationTypeId"       :   "' . $this->op_type_id_issue . '",
                    "operatorId"            :   "' . $this->operator_id . '",
                    "name"                  :   "' . Auth::user()->pax_name . '",
                    "email"                 :   "' . Auth::user()->pax_email . '",
                    "mobile"                :   "' . Auth::user()->pax_mobile . '",
                    "activationTime"        :   "' . Carbon::now() . '",
                    "masterTxnId"           :   "' . $data->ms_qr_no . '",
                    "qrType"                :   "' . $data->product_id . '",
                    "tokenType"             :   "' . $data->pass_id . '"
                 }
            }', 'application/json')
            ->post($this->base_url . '/qrcode/issueTrip')
            ->collect();

        return json_decode($response);

    }

    public function reloadStoreValueStatus($order)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                "data": {
                    "fare"          : "' . $order->total_price . '",
                    "supportType"   : "' . $this->media_type_id . '",
                    "qrType"        : "' . $order->product_id . '",
                    "tokenType"     : "' . $order->pass_id . '",
                    "operatorId"    : "' . $this->operator_id . '",
                    "masterTxnId"   : "' . $order->ms_qr_no . '"
                }
            }', 'application/json')
            ->post($this->base_url . '/qrcode/canReloadPass')
            ->collect();

        return json_decode($response);
    }

    public function reloadTripPassStatus($order)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                  "data": {
                    "fare"          : "' . $order->total_price . '",
                    "supportType"   : "' . $this->media_type_id . '",
                    "qrType"        : "' . $order->product_id . '",
                    "tokenType"     : "' . $order->pass_id . '",
                    "source"        : "' . $order->src_stn_id . '",
                    "destination"   : "' . $order->des_stn_id . '",
                    "operatorId"    : "' . $this->operator_id . '",
                    "masterTxnId"   : "' . $order->ms_qr_no . '"
                  }
                }', 'application/json')
            ->post($this->base_url . '/qrcode/canReloadPass')
            ->collect();

        return json_decode($response);
    }

    public function reloadStoreValuePass($order)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                "data": {
                    "fare"                  : "' . $order->total_price . '",
                    "tokenType"             : "' . $order->pass_id . '",
                    "operationTypeId"       : "' . $this->op_type_id_reload . '",
                    "operatorId"            : "' . $this->operator_id . '",
                    "name"                  : "' . Auth::user()->pax_name . '",
                    "email"                 : "' . Auth::user()->pax_email . '",
                    "mobile"                : "' . Auth::user()->pax_mobile . '",
                    "trips"                 : 45,
                    "activationTime"        : "' . Carbon::now() . '",
                    "operatorTransactionId" : "' . $order->sale_or_no . '",
                    "masterTxnId"           : "' . $order->ms_qr_no . '"
                },
                "payment": {
                    "pass_price"                : "' . $order->total_price . '",
                    "pgId"                      : "' . $this->pg_id . '",
                    "pg_order_id"               : "' . $order->pg_txn_no . '"
                }
            }', 'application/json')
            ->post($this->base_url . '/qrcode/reloadPass')
            ->collect();

        return json_decode($response);
    }

    public function reloadTripPass($order)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
              "data": {
                "fare"                  : "' . $order->total_price . '",
                "source"                : "' . $order->src_stn_id . '",
                "destination"           : "' . $order->des_stn_id . '",
                "tokenType"             : "' . $order->pass_id . '",
                "supportType"           : "' . $this->media_type_id . '",
                "qrType"                : "' . $order->product_id . '",
                "operationTypeId"       : "' . $this->op_type_id_reload . '",
                "operatorId"            : "' . $this->operator_id . '",
                "name"                  : "' . Auth::user()->pax_name . '",
                "email"                 : "' . Auth::user()->pax_email . '",
                "mobile"                : "' . Auth::user()->pax_mobile . '",
                "trips"                 : 45,
                "activationTime"        : "' . Carbon::now() . '",
                "operatorTransactionId" : "' . $order->sale_or_no . '",
                "masterTxnId"           : "' . $order->ms_qr_no . '"
              },
              "payment": {
                    "pass_price"                : "' . $order->total_price . '",
                    "pgId"                      : "' . $this->pg_id . '",
                    "pg_order_id"               : "' . $order->pg_txn_no . '"
              }
            }', 'application/json')
            ->post($this->base_url . '/qrcode/reloadPass')
            ->collect();

        return json_decode($response);

    }

    public function genTripPass($data)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                    "data": {
                        "fare"                      : "' . $data->total_price . '",
                        "supportType"               : "' . $data->media_type_id . '",
                        "qrType"                    : "' . $data->product_id . '",
                        "tokenType"                 : "' . $data->pass_id . '",
                        "operationTypeId"           : "' . $this->op_type_id_issue . '",
                        "source"                    : "' . $data->src_stn_id . '",
                        "destination"               : "' . $data->des_stn_id . '",
                        "operatorId"                : "' . $this->operator_id . '",
                        "name"                      : "' . Auth::user()->pax_name . '",
                        "email"                     : "' . Auth::user()->pax_email . '",
                        "mobile"                    : "' . Auth::user()->pax_mobile . '",
                        "activationTime"            : "' . Carbon::now() . '",
                        "operatorTransactionId"     : "' . $data->sale_or_no . '"
                    },
                    "payment": {
                           "pass_price"                : "' . $data->total_price . '",
                           "pgId"                      : "' . $this->pg_id . '",
                           "pg_order_id"               : "' . $data->pg_txn_no . '"
                    }
                }', 'application/json')
            ->post($this->base_url . '/qrcode/issuePass')
            ->collect();
        return json_decode($response);
    }

    public function getRefundInfo($data)
    {
        $op_id = $this->operator_id;
        $pass_id = $data->pass_id;
        $master_id = $data->ms_qr_no;

        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->get($this->base_url . "/qrcode/refund/info?tokenType=$pass_id&masterTxnId=$master_id&operatorId=$op_id")
            ->collect();

        return json_decode($response);
    }

    public function refundTicket($data, $refund_or_no)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                "data" : {
                    "operatorId"                        :"' . $data->data->operatorId . '",
                    "supportType"                       :"' . $data->data->supportType . '",
                    "qrType"                            :"' . $data->data->qrType . '",
                    "tokenType"                         :"' . $data->data->tokenType . '",
                    "source"                            :"' . $data->data->source . '",
                    "destination"                       :"' . $data->data->destination . '",
                    "remainingBalance"                  :"' . $data->data->remainingBalance . '",
                    "details":{
                        "registration":{
                            "processingFee"              : "' . $data->data->details->registration->processingFee . '",
                            "refundType"                 : "' . $data->data->details->registration->refundType . '",
                            "processingFeeAmount"        : "' . $data->data->details->registration->processingFeeAmount . '",
                            "refundAmount"               : "' . $data->data->details->registration->refundAmount . '",
                            "passPrice"                  : "' . $data->data->details->registration->passPrice . '"
                        },
                        "pass" : {
                            "processingFee"              : "' . $data->data->details->pass->processingFee . '",
                            "refundType"                 : "' . $data->data->details->pass->refundType . '",
                            "processingFeeAmount"        : "' . $data->data->details->pass->processingFeeAmount . '",
                            "refundAmount"               : "' . $data->data->details->pass->refundAmount . '",
                            "passPrice"                  : "' . $data->data->details->pass->passPrice . '"
                        }
                    },
                    "operatorTransactionId"             :"' . $refund_or_no . '",
                    "operationTypeId"                   :"' . $this->op_type_id_issue . '",
                    "masterTxnId"                       :"' . $data->data->masterTxnId . '",
                    "pgId"                              :"' . $this->pg_id . '"
                }
             }', 'application/json')
            ->post($this->base_url . '/qrcode/refund')
            ->collect();

        return json_decode($response);

    }

    public function graInfo($slave_id, $station_id)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->get($this->base_url . "/qrcode/penalty/status?transactionId=$slave_id&station=$station_id")
            ->collect();
        return json_decode($response);
    }

    public function applyGra($response, $order)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody(json_encode(json_decode('{
                "data": {
                    "fare"                      : "' . $order->total_price . '",
                    "destination"               : "' . $order->des_stn_id . '",
                    "refTxnId"                  : "' . $order->ref_sl_qr . '",
                    "tokenType"                 : "' . $order->pass_id . '",
                    "supportType"               : "' . $this->media_type_id . '",
                    "qrType"                    : "' . $order->product_id . '",
                    "operatorId"                : "' . $this->operator_id . '",
                    "operatorTransactionId"     : "' . $order->sale_or_no . '",
                    "activationTime"            : "' . time() . '",
                    "freeExitOptionId"          : 0,
                    "penalties"                 : ' . json_encode($response->data->penalties, JSON_OBJECT_AS_ARRAY) . ',
                    "overTravelCharges"         : ' . json_encode($response->data->overTravelCharges, JSON_OBJECT_AS_ARRAY) . '
                },
                "payment": {
                    "pass_price"                : "' . $order->total_price . '",
                    "pgId"                      : "' . $this->pg_id . '",
                    "pg_order_id"               : "' . $order->pg_txn_no . '"
                }
            }')), 'application/json')
            ->post($this->base_url . '/qrcode/penalty/issueToken')
            ->collect();

        return json_decode($response);

    }

    public function canIssuePass($product_id, $pass_id)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                "data": {
                    "fare"          : "1100",
                    "mobile"        : "' . Auth::user()->pax_mobile . '",
                    "operatorId"    : "' . $this->operator_id . '",
                    "qrType"        : "' . $product_id . '",
                    "supportType"   : "' . $this->media_type_id . '",
                    "tokenType"     : "' . $pass_id . '"
                }
            }', 'application/json')
            ->post($this->base_url . '/qrcode/canIssuePass');

        return json_decode($response);

    }

    public function canIssuePassTP($product_id, $pass_id)
    {
        $response = Http::withHeaders(['Authorization' => $this->auth])
            ->withBody('{
                "data": {
                    "fare"          : "1100",
                    "mobile"        : "' . Auth::user()->pax_mobile . '",
                    "operatorId"    : "' . $this->operator_id . '",
                    "qrType"        : "' . $product_id . '",
                    "source": 1,
                    "destination": 2,
                    "supportType"   : "' . $this->media_type_id . '",
                    "tokenType"     : "' . $pass_id . '"
                }
            }', 'application/json')
            ->post($this->base_url . '/qrcode/canIssuePass');

        return json_decode($response);

    }

}
