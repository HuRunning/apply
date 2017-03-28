<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
    protected $table = 'xjtu_apply_org';

    public $timestamps = false;

    public function auths()
    {
    	return $this->hasMany(Auth::class, 'orgid');
    }

    public function classrooms()
    {
    	return $this->hasMany(Classroom::class, 'orgid');
    }
}
