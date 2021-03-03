<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    //

    public function challenge()
    {
        return $this->hasMany('App\Challange');
    }
}
