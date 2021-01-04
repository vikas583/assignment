<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Sensortype extends Authenticatable
{
    use Notifiable;

    protected $table    = 'sensor_type';
    protected $fillable = [
        'type_name','icon' , 'unit', 'created_at','updated_at'
    ];

}
