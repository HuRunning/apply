<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'xjtu_apply_class';

    public $timestamps = false;

    public function org()
    {
    	return $this->belongsTo(Org::class, 'orgid');
    }

    public function infos()
    {
    	return $this->hasMany(Info::class, 'type');
    }
}
