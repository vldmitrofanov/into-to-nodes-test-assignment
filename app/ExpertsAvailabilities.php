<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertsAvailabilities extends Model
{
    public function slots()
    {
        return $this->hasMany('App\Bookings', 'schedule_group_id');
    }

    public function expert()
    {
        return $this->belongsTo('App\Experts', 'expert_id');
    }
}
