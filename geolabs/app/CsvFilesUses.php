<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CsvFilesUses extends Authenticatable
{
    use Notifiable;

    protected $table = 'csv_files_uses';
    protected $fillable = [
        'folder_name','date','time','created_at','updated_at'
    ];

 
}
