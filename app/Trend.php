<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * trendsテーブルのモデル
 */
class Trend extends Model
{
    protected $table = 'trends';

    public function currency() {
        return $this->belongsTo('App\Currency');
    }
}
