<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpertsController extends Controller
{
    public function getAll()
    {
        $experts = \App\Experts::orderBy('name', 'ASC')->get();

        return response()->json(['experts' => $experts]);
    }

    public function create(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $expert = new \App\Experts();
        $expert->name = $request->name;
        $expert->save();

        return response()->json(['status' => 'OK']);
    }
}
