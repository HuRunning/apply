<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addition extends Model
{
    protected $table = 'xjtu_apply_addition';

    public function info()
    {
    	return $this->belongsTo(Info::class);
    }
}
