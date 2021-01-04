<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Project extends Authenticatable
{
    use Notifiable;

    protected $table    = 'projects';
    
    protected $fillable = [
        'sensor_id','project_name', 'project_description', 'location','created_at','updated_at'
    ];

}
