<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $this->validate($req, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4'
        ]);

        $input = $req->all();
        $input['password'] = Hash::make($req->password);
        $user = User::create($input);
        if ($user) {
            return response(['message' => 'Account created successfully!', 'status' => true], 200);
        }
    }

    public function login(Request $req)
    {
        $this->validate($req, [
            'email' => 'required|email',
            'password' => ' required|string'
        ]);

        try {
            $client = new Client();
            return $client->request('POST', config('service.passport.end_point') . '/v1/oauth/token', [
                "form_params" => [
                    "grant_type" => "password",
                    "client_id" => config('service.passport.client_id'),
                    "client_secret" => config('service.passport.client_secret'),
                    "username" => $req->email,
                    "password" => $req->password,
                    "scope" => "*"
                ]
            ]);
        } catch (RequestException $e) {
            return response()->json(['status' => false, 'message' =>  $e->getMessage()]);
        }
    }
}
