<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    public function bookings()
    {
        return $this->hasMany('App\Bookings', 'client_id')->orderBy('date')->orderBy('time_start');
    }
}
