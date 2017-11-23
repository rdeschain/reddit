<?php
/**
 * Created by PhpStorm.
 * User: nbkn9dy
 * Date: 11/20/17
 * Time: 5:40 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
use DB;

class Post extends Model
{
    protected $fillable = [
        'reddit_id',
        'permalink',
        'url',
        'author'
    ];

}