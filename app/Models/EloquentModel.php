<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 22/11/2016
 * Time: 17:27
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EloquentModel extends Model
{
    public function first($id)
    {
        return Post::where(['id' => $id])->first();
    }
}