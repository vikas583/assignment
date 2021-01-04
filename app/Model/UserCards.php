<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserCards extends Authenticatable
{
    use Notifiable;

    protected $table = "user_cards";
    
    protected $fillable = [
        'user_id', 'card_number', 'name', 'exp_date'
    ];
}
