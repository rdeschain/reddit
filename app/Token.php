<?php
/**
 * Created by PhpStorm.
 * User: nbkn9dy
 * Date: 11/19/17
 * Time: 6:50 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = [
        'user_id', 'token'
    ];

}