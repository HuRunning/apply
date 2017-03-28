<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table = 'xjtu_apply_auth';

    public $timestamps = false;

    public function org()
    {
    	return $this->belongsTo(Org::class, 'auth');
    }
}
