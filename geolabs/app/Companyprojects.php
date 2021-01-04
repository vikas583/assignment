<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Companyprojects extends Authenticatable
{
    use Notifiable;

    protected $table = 'company_project';
    protected $fillable = [
        'company_id', 'project_id','created_at','updated_at'
    ];

    public function project_details(){
        return $this->hasOne('App\Project','id','project_id');
    }

    public function company_details(){
        return $this->hasOne('App\Company','id','company_id');
    }

}
