<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $fillable = [
        'username',
        'password',
    ];

    public function token()
    {
        return $this->hasOne('App\Token');
    }

}
