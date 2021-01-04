<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Sensors extends Authenticatable
{
    use Notifiable;

    protected $table = 'sensors';
    protected $fillable = [
        'sensor_type_id','sensor_name','latitude','longitude','formula','status','created_at','updated_at'
    ];

    public function sensor_type_details(){
        return $this->hasOne('App\Sensortype','id','sensor_type_id');
    }

}
