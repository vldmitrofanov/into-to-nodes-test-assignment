<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvailabilitiesController extends Controller
{
    public function getAll()
    {
        //$experts = \App\ExpertsAvailabilities::with('expert')->get()->sortBy(['expert.name', 'date', 'time_start'], SORT_REGULAR, false);
        $availabilities = \App\ExpertsAvailabilities::select('experts_availabilities.*')
            ->join('experts', 'experts_availabilities.expert_id', '=', 'experts.id')
            ->with('expert')
            ->orderBy('experts.name')
            ->orderBy('experts_availabilities.date')
            ->orderBy('experts_availabilities.time_start')
            ->get();
        return response()->json(['availabilities' => $availabilities]);
    }
}
