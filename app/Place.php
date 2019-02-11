<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = 'places';
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'coordinateX', 'coordinateY', 'user_id'];


    public function role()
    {
        return $this->belongsTo('App\User');
    }
}
