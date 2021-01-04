<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class FinalTransformedCsvData extends Authenticatable
{
    use Notifiable;

    protected $table = 'final_transformed_csv_data';
    protected $fillable = [
        'date_time','sensor_name','original_value','transformed_value','latitude','longitude','date','time','created_at','updated_at'
    ];

   
}
