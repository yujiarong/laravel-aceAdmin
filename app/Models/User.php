<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
	use SoftDeletes;


    protected $guarded    = [];
    protected $dates      = ['deleted_at'];
    protected $table      = 'users';
    /**
     * @param array $attributes
     */
}
