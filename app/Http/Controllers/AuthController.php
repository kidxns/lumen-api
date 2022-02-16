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
            return $client->request('POST', 'http://127.0.0.1:8000/v1/oauth/token', [
                "form_params" => [
                    "grant_type" => "password",
                    "client_id" => 2,
                    "client_secret" => "i0h3mrCnUClubqouaZKHId1WkM8DHZUrXMob3m8T",
                    "username" => $req->email,
                    "password" => $req->password,
                    "scope" => "*"
                ]
            ]);
        } catch (RequestException $e) {
            dd($e);
            return response()->json(['status' => false, 'message' =>  $e->getMessage()]);
        }
    }
}
