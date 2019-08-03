<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trend extends Model
{
    protected $table = 'trends';

    public function currency() {
        return $this->belongsTo('App\Currency');
    }
}
