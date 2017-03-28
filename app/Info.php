<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $table = 'xjtu_apply_info';

    public $timestamps = false;

    public function classroom()
    {
    	return $this->belongsTo(Classroom::class, 'type');
    }

    public function addition()
    {
    	return $this->hasOne(Addition::class);
    }
}
