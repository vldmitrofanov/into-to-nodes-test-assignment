<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function getAll()
    {
        $clients = \App\Clients::orderBy('name', 'ASC')->with(['bookings', 'bookings.expert'])->get();

        return response()->json(['clients' => $clients]);
    }

    public function create(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $expert = new \App\Clients();
        $expert->name = $request->name;
        $expert->save();

        return response()->json(['status' => 'OK']);
    }
}
