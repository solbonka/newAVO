<?php

namespace App\Http\Controllers\web;

use App\Clients\BiletAvtoApiClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class SandboxController
{
    public function index(Request $request)
    {
        $client = new BiletAvtoApiClient();
        try {
            $token = $client->status(7857739);
            dd($token);
        } catch (ConnectionException $e) {

        }

        return view('sandbox.index');
    }
}
