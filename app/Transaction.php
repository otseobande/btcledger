<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getValueAttribute()
    {
        return ($this->rate * $this->quantity) + $this->charges;
    }
}
