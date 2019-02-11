<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'role_id'];
    

    public function rol()
    {
        return $this->belongsTo('App\Rol');
    }

    public function place()
    {
        return $this->hasMany('App\Place');
    }
}
