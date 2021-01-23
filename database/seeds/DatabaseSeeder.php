<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function readCSV($csvFile, $array)
    {
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seed only february
        $csvFileName = "nodes_trial_assignment_test_experts_availability.csv";
        $csvFile = base_path('database/seeds/csv/' . $csvFileName);
        $experts_schedule = $this->readCSV($csvFile, array('delimiter' => ','));
        foreach ($experts_schedule as $sh) {
            $name = trim($sh[0]);
            if (empty($name)) continue;
            $exp = \App\Experts::where('name', $name)->first();
            if (empty($exp)) {
                $exp = new \App\Experts();
                $exp->name = $name;
                $exp->save();
            }
            $date = \Carbon\Carbon::parse($sh[1])->toDateString();
            $start = \Carbon\Carbon::parse($sh[2]);
            $end = \Carbon\Carbon::parse($sh[3]);
            if (empty($date) || empty($start) || empty($end)) continue;
            //if ($start->addHour() > $end) continue;
            $a = new \App\ExpertsAvailabilities();
            $a->date = $date;
            $a->time_start = $start->toTimeString();
            $a->time_end = $end->toTimeString();
            $exp->availabilities()->save($a);

            // seeding bookings
            do {
                $slot_start = $start->toTimeString();
                $slot_end = $start->addHour()->toTimeString();
                $booking = new \App\Bookings();
                $booking->date = $date;
                $booking->time_start = $slot_start;
                $booking->time_end = $slot_end;
                $booking->schedule_group_id = $a->id;
                $exp->bookings()->save($booking);
            } while ($start < $end);
        }

        $client = new \App\Clients();
        $client->name = 'Dafault Client';
        $client->save();

        $csvFileName = "nodes_trial_assignment_test_client_schedule.csv";
        $csvFile = base_path('database/seeds/csv/' . $csvFileName);
        $clients_schedule = $this->readCSV($csvFile, array('delimiter' => ','));
        foreach ($clients_schedule as $cs) {
            if (empty($cs[0])) continue;
            $date = \Carbon\Carbon::parse($cs[0])->toDateString();
            $start = \Carbon\Carbon::parse($cs[1]);
            $end = \Carbon\Carbon::parse($cs[2]);
            $descr = trim($cs[3]);
            if (empty($descr) || empty($date) || empty($start) || empty($end)) continue;
            //if ($start->addHour() > $end) continue;
            $b = new \App\Bookings();
            $b->date = $date;
            $b->time_start = $start->toTimeString();
            $b->time_end = $end->toTimeString();
            $b->description = $descr;
            $client->bookings()->save($b);
        }
    } //end of run statement
}
