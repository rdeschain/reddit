<?php
/**
 * Created by PhpStorm.
 * User: nbkn9dy
 * Date: 11/21/17
 * Time: 5:38 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'tag_id',
        'reddit_id'
    ];

}