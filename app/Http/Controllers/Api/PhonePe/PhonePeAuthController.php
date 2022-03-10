<?php

namespace App\Http\Controllers\Api\PhonePe;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class PhonePeAuthController extends Controller
{

    public $salt_key;
    public $salt_index;
    public $x_client_id;

    public function __construct()
    {
        $this->salt_key = env("PHONEPAY_SLAT_KEY");
        $this->salt_index = env("PHONEPAY_SLAT_INDEX");
        $this->x_client_id = env("PHONEPAY_CLIENT_ID");
    }

    public function fetchAccessToken($grantToken): Collection
    {
        // CREATING BASE 64 OF TOKEN
        $token = base64_encode('{"grantToken": "'.$grantToken.'"}');

        // CREATING REQUEST
        $request = '{"request": "'.$token.'"}';

        // CREATING X-VERIFY
        $x_verify = hash('sha256', $token."/v3/service/auth/access".$this->salt_key)."###".$this->salt_index;

        return Http::withHeaders([
            'x-verify' => $x_verify,
            'x-client-id' => $this->x_client_id
        ])
            ->withBody($request, 'application/json')
            ->post('https://apps-uat.phonepe.com/v3/service/auth/access')->collect();

    }

    public function getUserDetails($accessToken): Collection
    {
        $x_verify = hash('sha256', "/v3/service/userdetails".$this->salt_key)."###".$this->salt_index;

        return Http::withHeaders([
            'x-verify' => $x_verify,
            'x-client-id' => $this->x_client_id,
            'x-access-token' => $accessToken
        ]) -> get('https://apps-uat.phonepe.com/v3/service/userdetails')->collect();

    }

}
