<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\PhonePe\PhonePeAuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MainController extends Controller
{
    public function index()
    {
        return Inertia::render('Index');
    }

    public function auth(Request $request)
    {
        $api = new PhonePeAuthController();

        $token = $api->fetchAccessToken($request->input('token'));
        $tokenRes = json_decode($token);

        if ($tokenRes->success)
        {
            $user = $api->getUserDetails($tokenRes->data->accessToken);
            $userRes = json_decode($user);

            if ($userRes->success)
            {
                $oldUser = DB::table('users')
                    ->where('pax_mobile', '=', $userRes->data->phoneNumber)
                    ->first();

                if ($oldUser == null) {

                    User::create([
                        'pax_name' => $userRes->data->name,
                        'pax_email' => $userRes->data->primaryEmail,
                        'pax_mobile' => $userRes->data->phoneNumber,
                        'is_verified' => $userRes->data->isEmailVerified,
                    ]);

                } else {

                    DB::table('users')
                        ->where('pax_mobile', '=', $userRes->data->phoneNumber)
                        ->update([
                            'pax_name' => $userRes->data->name,
                            'pax_email' => $userRes->data->primaryEmail,
                            'is_verified' => $userRes->data->isEmailVerified,
                        ]);

                }

                $_user = User::where('pax_mobile', '=', $userRes->data->phoneNumber)->first();
                Auth::login($_user);
                return redirect()->route('products');
            }
            else
            {
                dd($userRes);
            }
        }
        else
        {
            dd($tokenRes);
        }
    }
}
