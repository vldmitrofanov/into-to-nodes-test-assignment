<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Clients', 'client_id');
    }

    public function expert()
    {
        return $this->belongsTo('App\Experts', 'expert_id');
    }
}
