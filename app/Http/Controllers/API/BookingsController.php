<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingsController extends Controller
{
    public const STARTHOUR = '09:00';
    public const ENDHOUR = '18:00';

    public function getAll()
    {
        $clients = \App\Clients::orderBy('name', 'ASC')->with('bookings')->get();

        return response()->json(['clients' => $clients]);
    }

    public function autoSet($client_id, Request $request)
    {
        /*
        TODO: add month and other options
        $this->validate($request, [
            'something' => 'required|string',
        ]);
        */

        $client = \App\Clients::findOrFail($client_id);

        $dates = \App\Bookings::where('client_id', $client->id)->distinct()->select('date')->orderBy('date')->pluck('date');

        $excluded = \App\Bookings::whereNotNull('expert_id')
            ->whereNotNull('client_id')
            ->distinct()
            ->select('expert_id')
            ->orderBy('expert_id')
            ->pluck('expert_id');

        $ranks = \App\Bookings::whereNotNull('expert_id')
            ->groupBy('expert_id')
            ->orderBy('count')
            ->select('bookings.*', DB::raw('COUNT(expert_id) as count'))
            ->pluck('expert_id')
            ->toArray();

        foreach ($dates as $date) {
            $this->autoSetForDate($date, $client, $excluded, $ranks);
        }

        return response()->json(['status' => 'OK']);
    }

    public function unsetAll($client_id, Request $request)
    {
        $dates = \App\Bookings::where('client_id', $client_id)->whereNotNull('expert_id')->get();
        foreach ($dates as $date) {
            $date->client_id = null;
            $date->save();
        }
        return response()->json(['status' => 'OK']);
    }

    private function findAndAssingBookingBlock($date, $start, $client, &$expert_ids, $ranks)
    {
        $block_end = $start;
        $available_bookings = \App\Bookings::whereNull('client_id')
            ->whereNotNull('expert_id')
            ->where('date',  $date)
            ->where('time_start', '>=', $start->toTimeString())
            ->where('time_start', '<', $block_end->addHour()->toTimeString())
            ->whereNotIn('expert_id', $expert_ids)
            ->get();

        if ($available_bookings->count() > 0) {
            $sorted_bookings = $available_bookings->sortBy(function ($person) use ($ranks) {
                return array_search($person->expert_id, $ranks);
            });
            $b = $sorted_bookings->values()->first();
            $b->client_id = $client->id;
            $b->update();
            $start = \Carbon\Carbon::parse($date . " " . $b->time_end);
            $expert_ids[] = $b->expert_id;
            return $start;
        }
        return null;
    }

    private function autoSetForDate($date, \App\Clients $client, &$expert_ids, $ranks)
    {
        /* 
            to debug to console use:
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln("<info> " . $date . "</info>");
        */
        $bookings = \App\Bookings::where('client_id', $client->id)->where('date', $date)->orderBy('time_start')->get();
        $start = \Carbon\Carbon::parse($date . " " . self::STARTHOUR);
        $end = \Carbon\Carbon::parse($date . " " . self::ENDHOUR);
        $last = sizeof($bookings) - 1;

        foreach ($bookings as $key => $b) {
            $cur_date_start = \Carbon\Carbon::parse($date . " " . $b->time_start);
            $cur_date_end = \Carbon\Carbon::parse($date . " " . $b->time_end);

            if ($cur_date_start == $start) {
                $start = $cur_date_end;
            } elseif ($cur_date_start > $start) {
                // if there is a gap before
                while ($cur_date_start > $start && $cur_date_start->diffInHours($start) >= 1) {
                    $result = $this->findAndAssingBookingBlock($date, $start, $client, $expert_ids, $ranks);
                    if ($result !== null) {
                        $start = $result;
                    } else {
                        $start->addHour();
                    }
                }
                $start = $cur_date_end;
            }
            // if this is the last booking and there is a gap longer than an hour
            if ($key == $last) {
                $start = $cur_date_end;
                while ($end > $start && $end->diffInHours($start) >= 1) {
                    $result = $this->findAndAssingBookingBlock($date, $start, $client, $expert_ids, $ranks);
                    if ($result !== null) {
                        $start = $result;
                    } else {
                        $start->addHour();
                    }
                }
            }
        }
    }
}
