<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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


        foreach ($dates as $date) {
            $this->autoSetForDate($date, $client);
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

    private function autoSetForDate($date, \App\Clients $client)
    {
        $bookings = \App\Bookings::where('client_id', $client->id)->where('date', $date)->orderBy('time_start')->get();
        $start = \Carbon\Carbon::parse($date . " " . self::STARTHOUR);
        $end = \Carbon\Carbon::parse($date . " " . self::ENDHOUR);
        $previous_end = $start;
        $last = sizeof($bookings) - 1;
        $expert_ids = [];
        // debug: 
        //$output = new \Symfony\Component\Console\Output\ConsoleOutput();
        // $output->writeln("<info> " . $date . "</info>");
        foreach ($bookings as $key => $b) {
            // $output->writeln("<info></info>");
            $cur_date_start = \Carbon\Carbon::parse($date . " " . $b->time_start);
            $cur_date_end = \Carbon\Carbon::parse($date . " " . $b->time_end);

            // $output->writeln("<info> Start of the set:  " . $cur_date_start->toDateTimeString() . "</info>");

            if ($cur_date_start == $start) {
                $start = $cur_date_end;
                $previous_end = $start;
            } elseif ($cur_date_start > $start) {
                // if there is a gap before
                while ($cur_date_start > $start && $cur_date_start->diffInHours($start) >= 1) {
                    // $output->writeln("<info> Before statement. Diff:  " . $cur_date_start->diffInHours($start) . "</info>");
                    // $output->writeln("<info> Cur start " . $cur_date_start->toDateTimeString() . "----- Start " . $start->toDateTimeString() . "</info>");
                    $available_booking = \App\Bookings::whereNull('client_id')
                        ->whereNotNull('expert_id')
                        ->where('date',  $date)
                        ->where('time_start', '>=', $start->toTimeString())
                        ->where('time_start', '<', $previous_end->addHour()->toTimeString())
                        ->whereNotIn('expert_id', $expert_ids)
                        ->inRandomOrder()
                        ->first();
                    if (!empty($available_booking)) {
                        // $output->writeln("<warning> Found" . $available_booking->time_start . "----- End" . $available_booking->time_end . "</warning>");
                        $available_booking->client_id = $client->id;
                        $available_booking->update();
                        $start = \Carbon\Carbon::parse($date . " " . $available_booking->time_end);
                        $previous_end = $start;
                        $expert_ids[] = $available_booking->expert_id;
                    } else {
                        $start->addHour();
                    }
                }
                $start = $cur_date_end;
            }
            // if this is the last booking and there is a gap longer than an hour
            if ($key == $last) {
                $start = $cur_date_end;
                $previous_end = $start;

                while ($end > $start && $end->diffInHours($start) >= 1) {
                    // $output->writeln("<info> End statement. Diff:  " . $end->diffInHours($start) . "</info>");
                    // $output->writeln("<info> Cur end " . $cur_date_end->toDateTimeString() . " ----- Start " . $start->toDateTimeString() . "</info>");
                    $available_booking = \App\Bookings::whereNull('client_id')
                        ->whereNotNull('expert_id')
                        ->where('date',  $date)
                        ->where('time_start', '>=', $start->toTimeString())
                        ->where('time_start', '<', $previous_end->addHour()->toTimeString())
                        ->whereNotIn('expert_id', $expert_ids)
                        ->inRandomOrder()
                        ->first();
                    if (!empty($available_booking)) {
                        // $output->writeln("<info> Found" . $available_booking->time_start . "----- End" . $available_booking->time_end . "</info>");
                        $available_booking->client_id = $client->id;
                        $available_booking->save();
                        $start = \Carbon\Carbon::parse($date . " " . $available_booking->time_end);
                        $previous_end = $start;
                        $expert_ids[] = $available_booking->expert_id;
                    } else {
                        $start->addHour();
                    }
                }
            }
        }
        // $output->writeln("<info> ============================ </info>");
        // $output->writeln("<info>  </info>");
    }
}
