<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CsvData extends Authenticatable
{
    use Notifiable;

    protected $table = 'csv_data';
    protected $fillable = [
        'date_time','sensor_name','sensor_value','date','time','created_at','updated_at'
    ];

   
}
