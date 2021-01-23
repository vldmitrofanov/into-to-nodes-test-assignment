<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experts extends Model
{
    public function bookings()
    {
        return $this->hasMany('App\Bookings', 'expert_id');
    }

    public function availabilities()
    {
        return $this->hasMany('App\ExpertsAvailabilities', 'expert_id');
    }
}
