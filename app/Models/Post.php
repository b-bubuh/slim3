<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 22/11/2016
 * Time: 17:28
 */


namespace App\Models;


class Post extends EloquentModel
{
    public static function getPosts()
    {
        return Post::orderBy('created_at', 'DESC')->get();
    }
}